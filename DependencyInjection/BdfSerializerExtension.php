<?php

namespace Bdf\SerializerBundle\DependencyInjection;

use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * SerializerExtension.
 */
class BdfSerializerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('serializer.yaml');

        $this->configureCache($config, $container);
    }

    public function configureCache(array $config, ContainerBuilder $container)
    {
        if (isset($config['cache'])) {
            $ref = $this->createCacheReference('bdf_serializer.cache', $config['cache'], $container);

            if (null !== $ref) {
                $serializerDefinition = $container->getDefinition('bdf_serializer.metadata_factory');
                $serializerDefinition->replaceArgument(1, $ref);
            }
        }
    }

    private function createCacheReference(string $namespace, array $config, ContainerBuilder $container): ?Reference
    {
        if (isset($config['service'])) {
            return new Reference($config['service']);
        }

        if (isset($config['pool'])) {
            $definition = $container->register($namespace, Psr16Cache::class);
            $definition->addArgument(new Reference($config['pool']));

            return new Reference($namespace);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container): ?ConfigurationInterface
    {
        return new Configuration();
    }
}
