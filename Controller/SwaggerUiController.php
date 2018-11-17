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

use SRedbull\ApiDocBundle\Service\OpenApiService;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

/**
 * Class SwaggerUiController.
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
     * The Open Api service.
     *
     * @var OpenApiService $openApiService
     */
    private $openApiService;

    /**
     * SwaggerUiController constructor.
     *
     * @param OpenApiService   $openApiService The Open Api service.
     * @param Twig_Environment $twig           The twig environment.
     */
    public function __construct(OpenApiService $openApiService, Twig_Environment $twig)
    {
        $this->openApiService = $openApiService;
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
        return new Response(
            $this->twig->render('@SRedbullApiDoc/SwaggerUi/index.html.twig', ['swagger_data' => ['spec' => $this->openApiService->getSpec()]]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/html']
        );
    }

}
