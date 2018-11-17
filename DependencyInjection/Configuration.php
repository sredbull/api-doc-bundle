<?php declare (strict_types = 1);

/*
 * This file is part of the SRedbullApiDocBundle package.
 *
 * (c) Sven Roodbol <roodbol.sven@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SRedbull\ApiDocBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
final class Configuration implements ConfigurationInterface
{

    /**
     * Get the config tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('s_redbull_api_doc')
            ->children()
                ->arrayNode('documentation')
                    ->children()
                    ->arrayNode('info')
                        ->children()
                            ->scalarNode('title')->end()
                            ->scalarNode('description')->end()
                            ->scalarNode('termsOfService')->end()
                            ->arrayNode('contact')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('url')->end()
                                    ->scalarNode('email')->end()
                                ->end()
                            ->end()
                            ->arrayNode('license')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('url')->end()
                                ->end()
                            ->end()
                            ->scalarNode('version')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
