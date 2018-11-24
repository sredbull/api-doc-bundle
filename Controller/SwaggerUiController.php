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

use Doctrine\Common\Annotations\AnnotationReader;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Object_;
use SRedbull\ApiDocBundle\Describer\RouteDescriber;
use SRedbull\ApiDocBundle\Service\OpenApiService;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SwaggerUiController.
 */
final class SwaggerUiController
{

    /**
     * The twig environment.
     *
     * @var \Twig_Environment $twig
     */
    private $twig;

    /**
     * The Open Api service.
     *
     * @var OpenApiService $openApiService
     */
    private $openApiService;

    private $kernel;

    private $routeDescriber;

    /**
     * SwaggerUiController constructor.
     *
     * @param KernelInterface $kernel
     * @param OpenApiService $openApiService The Open Api service.
     * @param \Twig_Environment $twig The twig environment.
     * @param RouteDescriber $router
     */
    public function __construct(KernelInterface $kernel, OpenApiService $openApiService, \Twig_Environment $twig, RouteDescriber $routeDescriber)
    {
        $this->openApiService = $openApiService;
        $this->twig = $twig;
        $this->kernel = $kernel;
        $this->routeDescriber = $routeDescriber;
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
        $spec = $this->openApiService->getSpec();
//        $pathDocs = [];
//
//        $reader = new AnnotationReader();
//        $docBlockFactory = DocBlockFactory::createInstance();
//        $finder = new Finder();
//        $finder->files()->in($this->kernel->getRootDir() . '/Controller');
//        foreach ($finder as $file) {
//            $reflection = new \ReflectionClass($this->getNamespace($file));
//            foreach ($reflection->getMethods() as $method) {
//                if ($method->class !== $reflection->getName()) {
//                    continue;
//                }
//
//                $docBlock = null;
//                if ($method->getDocComment() !== false) {
//                    $docBlock = $docBlockFactory->create($method->getDocComment());
//                }
//
//                if ($docBlock->hasTag('Route') === false) {
//                    continue;
//                }
//
//                /** @var Route $route */
//                $route = $reader->getMethodAnnotation($method, Route::class);
//                foreach ($route->getMethods() as $httpMethod) {
//                    $parameters = [];
//                    if ($docBlock->hasTag('param') === true) {
//                         /** @var Param $param */
//                        foreach ($docBlock->getTagsByName('param') as $param) {
//                            /** @var Object_ $type */
//                            $type = $param->getType();
//                            if ($type instanceof Object_ === true && strpos($type->getFqsen()->getName(), 'Entity') === false) {
//                                continue;
//                            }
//
//                            /** @var Compound $type */
//                            if ($type instanceof Compound === true) {
//                                foreach ($type->getIterator() as $item) {
//                                    if ($item instanceof Object_ === true && strpos($item->getFqsen()->getName(), 'Entity') === false) {
//                                        continue;
//                                    }
//                                }
//                            }
//
//                            $required = 'true';
//                            $schema = [];
//                            if ($param->getType() instanceof Compound === true ) {
//                                foreach ($param->getType() as $type) {
//                                    if ($type instanceof Null_ === true) {
//                                        $required = '';
//                                    }
//
//                                    /** @var Object_ $type */
//                                    if ($type instanceof Object_ === true && strpos($type->getFqsen()->getName(), 'Entity') !== false) {
//                                        $schema = [
//                                            'type' => 'integer',
//                                            'example' => '1',
//                                        ];
//                                    }
//                                }
//                            }
//
//                            if ($param->getType() instanceof Object_ === true && strpos($type->getFqsen()->getName(), 'Entity') !== false ) {
//                                $schema = [
//                                    'type' => 'integer',
//                                    'example' => '1',
//                                ];
//                            }
//
//                            $parameters[] = [
//                                'name' => $param->getVariableName(),
//                                'in' => 'path',
//                                'description' => $param->getDescription()->render(),
//                                'required' => $required,
//                                'schema' => $schema,
//                            ];
//                        }
//                    }
//
//                    $responses = [];
//
//
//                    $pathDocs[$route->getPath()] = [
//                        strtolower($httpMethod) => [
//                            'tags' => [str_replace('Controller', '', $reflection->getShortName())],
//                            'description' => $docBlock->getDescription()->render(),
//                            'summary' => $docBlock->getSummary(),
//                            'operationId' => $reflection->getShortName() . '::' . $method->getName() . '()',
//                            'parameters' => $parameters,
//                            'responses' => $responses,
//                        ],
//                    ];
//                }
//
//            }
//        }
//
//        dump($pathDocs);
//
//        $spec['paths'] = \array_merge($spec['paths'], $pathDocs);
//        \ksort($spec['paths']);
//        $spec['security'] = [];
//        $securitySchemes = array_keys($spec['components']['securitySchemes'] ?? []);
//        foreach($securitySchemes as $securityScheme) {
//            $spec['security'][] = [$securityScheme => []];
//        }
//
//        dump($spec);

        return new Response(
            $this->twig->render('@SRedbullApiDoc/SwaggerUi/index.html.twig', ['swagger_data' => ['spec' => $spec]]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/html']
        );
    }

    private function getNamespace(SplFileInfo $file): string
    {
        $lines = file($file->getPathname());
        $namespace = preg_filter('/^namespace /', '', $lines);
        $namespace = preg_replace('~[;\n]+~', '', array_pop($namespace)) . '\\';
        $filename = str_replace('.php', '', $file->getFilename());

        return  '\\' . $namespace . $filename;
    }

}
