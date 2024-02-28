<?php

namespace Bdf\SerializerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
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
                ->arrayNode('denormalization_options')
                    ->info('Default options for denormalization')
                    ->ignoreExtraKeys(false)
                    ->children()
                        ->scalarNode('json_options')->defaultValue(0)
                            ->beforeNormalization()
                                ->always(function ($v) { return $this->normalizeJsonOptions($v); })
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('normalization_options')
                    ->info('Default options for normalization')
                    ->ignoreExtraKeys(false)
                    ->children()
                        ->scalarNode('json_options')->defaultValue(0)
                            ->beforeNormalization()
                                ->always(function ($v) { return $this->normalizeJsonOptions($v); })
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function normalizeJsonOptions($config)
    {
        $options = 0;

        foreach ((array) $config as $option) {
            foreach (explode('|', $option) as $part) {
                $part = trim($part);
                $options |= is_numeric($part) ? (int) $part : constant($part);
            }
        }

        return $options;
    }
}
