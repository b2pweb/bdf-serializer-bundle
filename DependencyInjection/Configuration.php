<?php

namespace Bdf\SerializerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bdf_serializer');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('cache')
                    ->info('The metadata cache service. Should implement Psr\SimpleCache\CacheInterface.')
                    ->children()
                        ->scalarNode('pool')->end()
                        ->scalarNode('service')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
