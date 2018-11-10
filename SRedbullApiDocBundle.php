<?php

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SRedbull\ApiDocBundle;

use SRedbull\ApiDocBundle\DependencyInjection\Compiler\ConfigurationPass;
use SRedbull\ApiDocBundle\DependencyInjection\Compiler\TagDescribersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SRedbullApiDocBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationPass());
        $container->addCompilerPass(new TagDescribersPass());
    }
}
