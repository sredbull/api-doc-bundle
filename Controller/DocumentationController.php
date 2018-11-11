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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class DocumentationController.
 *
 * @todo invoke logic to service.
 */
final class DocumentationController
{

    /**
     * The kernel interface.
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * SwaggerUiController constructor.
     *
     * @param KernelInterface $kernel The kernel interface.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Invoke the controller.
     *
     * @return JsonResponse
     *
     * @throws \Exception Thrown when the command could not be executed for some reason.
     */
    public function __invoke(): JsonResponse
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

        return new JsonResponse($spec);
    }

}
