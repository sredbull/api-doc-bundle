<?php declare (strict_types = 1);

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SRedbull\ApiDocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig_Environment;

/**
 * Class SwaggerUiController.
 *
 * @todo invoke logic to service.
 */
final class SwaggerUiController
{

    /**
     * The twig environment.
     *
     * @var Twig_Environment $twig
     */
    private $twig;

    /**
     * The kernel interface.
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * SwaggerUiController constructor.
     *
     * @param KernelInterface  $kernel The kernel interface.
     * @param Twig_Environment $twig   The twig environment.
     */
    public function __construct(KernelInterface $kernel, Twig_Environment $twig)
    {
        $this->kernel = $kernel;
        $this->twig = $twig;
    }

    /**
     * Invoke the controller.
     *
     * @return Response
     *
     * @throws \Exception          Thrown when the command could not be executed for some reason.
     * @throws \Twig_Error_Loader  Thrown when an error occurs during template loading.
     * @throws \Twig_Error_Runtime Thrown when an error occurs at runtime.
     * @throws \Twig_Error_Syntax  Thrown when a syntax error occurs during lexing or parsing of a template.
     */
    public function __invoke(): Response
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'oa:generate',
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);

        // @todo We should validate/cache the spec.
        $spec = \json_decode($output->fetch());

        return new Response(
            $this->twig->render('@SRedbullApiDoc/SwaggerUi/index.html.twig', ['swagger_data' => ['spec' => $spec]]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/html']
        );
    }

}
