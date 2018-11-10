<?php

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) SRedbull
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SRedbull\ApiDocBundle\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class SwaggerUiController
{

    private $twig;

    /**
     * @param ContainerInterface $generatorLocator
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, $area = 'default')
    {
        $spec['info'] = ['title' => 'test'];
        $spec['basePath'] = $request->getBaseUrl();

        return new Response(
            $this->twig->render('@SRedbullApiDoc/SwaggerUi/index.html.twig', ['swagger_data' => ['spec' => $spec]]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/html']
        );
    }

}
