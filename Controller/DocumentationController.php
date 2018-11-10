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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class DocumentationController
{

    public function __invoke(Request $request, $area = 'default')
    {
        $spec['info'] = ['title' => 'test'];
        $spec['basePath'] = $request->getBaseUrl();

        return new JsonResponse($spec);
    }

}
