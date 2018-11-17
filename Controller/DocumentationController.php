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
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DocumentationController.
 *
 * @todo invoke logic to service.
 */
final class DocumentationController
{

    /**
     * The Open Api service.
     *
     * @var OpenApiService $openApiService
     */
    private $openApiService;

    /**
     * SwaggerUiController constructor.
     *
     * @param OpenApiService $openApiService The Open Api service.
     */
    public function __construct(OpenApiService $openApiService)
    {
        $this->openApiService = $openApiService;
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
        return new JsonResponse($this->openApiService->getSpec());
    }

}
