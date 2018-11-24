<?php declare (strict_types = 1);

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SRedbull\ApiDocBundle\Describer;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\ContextFactory;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class RouteDescriber
 */
class RouteDescriber
{

    private const DEFAULT_EXCEPTION_CODE = 500;

    /**
     * The ignored routes.
     *
     * @var array
     */
    private static $ignoredRoutes = [
        '/',
        '/docs.json',
    ];

    /**
     * The router interface.
     *
     * @var RouterInterface $router
     */
    private $router;

    /**
     * RouteDescriber constructor.
     *
     * @param RouterInterface $router The router interface.
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Describe all the routes and return the Open Api specification with the defined paths.
     *
     * @param array $oas The open api specification.
     *
     * @return array
     *
     * @throws \ReflectionException When parsing the routes fails.
     */
    public function describe(array $oas): array
    {
        $this->parseRoutes();

        $oas['paths'] += $this->parseRoutes();

        ksort($oas['paths']);

        return $oas;
    }

    /**
     * Parse the route collection and return a human readable layout
     *
     * @return array
     *
     * @throws \ReflectionException When the responses could not parsed.
     */
    public function parseRoutes(): array
    {
        $routeColelction = $this->getRouteCollection()->getIterator();

        $routes = [];
        foreach ($routeColelction as $route) {
            if ($this->isRouteIgnored($route) === true ) {
                continue;
            }

            $docBlock = $this->getRouteDocBlock($route);
            $routeDetails = $this->getRouteDetails($docBlock);
            foreach ($route->getMethods() as $httpMethod) {
                $routes[$route->getPath()] = [
                    strtolower($httpMethod) => [
                        'tags' => [$this->getTag($route)],
                        'description' => $routeDetails['description'],
                        'summary' => $routeDetails['summary'],
                        'operationId' => $this->getMethod($route),
                        'parameters' => [],
                        'responses' => $this->getResponses($route),
                    ],
                ];
            }
        }

        return $routes;
    }

    /**
     * Get the router.
     *
     * @return RouterInterface
     */
    private function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Get the route collection.
     *
     * @return RouteCollection
     */
    private function getRouteCollection(): RouteCollection
    {
        return $this->getRouter()->getRouteCollection();
    }


    /**
     * Check if the route should be ignored.
     *
     * Routes beginning with an underscore or in the $ignoredRoutes are ignored.
     *
     * @param Route $route The route.
     *
     * @return bool
     */
    private function isRouteIgnored(Route $route): bool
    {
        return
            \substr($route->getPath(), 1, 1) === '_' ||
            \in_array($route->getPath(), self::$ignoredRoutes, true) === true
        ;
    }

    /**
     * Get the controller namespace.
     *
     * @param Route $route The route.
     *
     * @return string|null
     */
    private function getController(Route $route): ?string
    {
        if ($route->getDefault('_controller') === null) {
            return null;
        }

        return explode('::', $route->getDefault('_controller'))[0];
    }

    /**
     * Get the tag of the route.
     *
     * @param Route $route The route.
     *
     * @return string
     */
    private function getTag(Route $route): string
    {
        $class = $this->getController($route);
        $strippedClass = explode('\\', $class);

        return str_replace('Controller', '', end($strippedClass));
    }

    /**
     * Get the method name.
     *
     * @param Route $route The route.
     *
     * @return string|null
     */
    private function getMethod(Route $route): ?string
    {
        if ($route->getDefault('_controller') === null) {
            return null;
        }

        return explode('::', $route->getDefault('_controller'))[1];
    }


    /**
     * Get the route doc block.
     *
     * @param Route $route The route.
     *
     * @return DocBlock
     *
     * @throws \ReflectionException When the route class could not be reflected.
     */
    private function getRouteDocBlock(Route $route): DocBlock
    {
        $reflectionMethod = new \ReflectionMethod($this->getController($route), $this->getMethod($route));

        $docBlockFactory = DocBlockFactory::createInstance();
        $contextFactory = (new ContextFactory())->createFromReflector($reflectionMethod);

        return $docBlockFactory->create($reflectionMethod, $contextFactory);
    }


    /**
     * Get the description and summary.
     *
     * @param DocBlock $docBlock The docBlock.
     *
     * @return array
     */
    private function getRouteDetails(DocBlock $docBlock): array
    {
        return [
            'description' => $docBlock->getDescription()->render(),
            'summary' => $docBlock->getSummary(),
        ];
    }

    private function getRouteParameters()
    {
        // things
    }

    /**
     * Get the responses.
     *
     * @param Route $route The route.
     *
     * @throws \ReflectionException Thrown when the method could not be reflected.
     *
     * @return array
     */
    private function getResponses(Route $route): array
    {
        $method = new \ReflectionMethod(
            $this->getController($route),
            $this->getMethod($route)
        );

        $returnResponse = $this->getReturnResponse($method);
        $exceptionResponses = $this->getExceptionResponses($method);
        $responses = $returnResponse + $exceptionResponses;

        return $responses;
    }

    /**
     * Get the return response.
     *
     * @param \ReflectionMethod $method The method.
     *
     * @return array
     */
    private function getReturnResponse(\ReflectionMethod $method): array
    {
        if ($method->getReturnType() === null) {
            return [];
        }

        $class = $method->getReturnType()->getName();
        // @todo dingen
        $httpCode = \defined("$class . ::HTTP_CODE") === true ? "$class . ::HTTP_CODE" : 'default';
        $oasRef = explode('\\', $method->getReturnType()->getName());

        return [
            $httpCode => [
                '$ref' => '#/components/responses/' . end($oasRef),
            ],
        ];
    }

    /**
     * Get the exception response(s).
     *
     * @param \ReflectionMethod $method The method.
     *
     * @return array
     *
     * @throws \ReflectionException When the exception code could not parsed.
     */
    private function getExceptionResponses(\ReflectionMethod $method): array
    {
        $docBlockFactory = DocBlockFactory::createInstance();
        $contextFactory = (new ContextFactory())->createFromReflector($method);

        $docBlock = $docBlockFactory->create($method, $contextFactory);

        if ($docBlock->hasTag('throws') === false) {
            return [];
        }

        $responses = [];
        /** @var Throws $exception */
        foreach ($docBlock->getTagsByName('throws') as $exception) {
            /** @var Object_ $exceptionType */
            $exceptionType = $exception->getType();
            $exceptionClass = $exceptionType->getFqsen()->__toString();
            $exceptionName = $exceptionType->getFqsen()->getName();
            $exceptionCode = $this->getExcetionCode($exceptionClass);
            $responses[$exceptionCode] = ['$ref' => '#/components/responses/' . $exceptionName];
        }

        return $responses;
    }

    /**
     * Get the exception code.
     *
     * @param string $class The classname.
     *
     * @return int
     *
     * @throws \ReflectionException When the class could not be reflected.
     */
    private function getExcetionCode(string $class): int
    {
        $code = self::DEFAULT_EXCEPTION_CODE;
        $reflectionCLass = new \ReflectionClass($class);
        $reflectionParmaters = $reflectionCLass->getConstructor()->getParameters();
        foreach($reflectionParmaters as $reflectionParmater) {
            if ($reflectionParmater->getName() !== 'code') {
                continue;
            }

            $code = $reflectionParmater->getDefaultValue();
        }

        return $code;
    }

}