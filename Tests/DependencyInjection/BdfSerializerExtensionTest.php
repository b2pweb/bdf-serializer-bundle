<?php

namespace Bdf\SerializerBundle\Tests\DependencyInjection;

use Bdf\Serializer\SerializerInterface;
use Bdf\SerializerBundle\DependencyInjection\BdfSerializerExtension;
use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerLoaderPass;
use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerNormalizerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;

/**
 * BdfSerializerExtensionTests.
 */
class BdfSerializerExtensionTest extends TestCase
{
    public function testDefaultConfig()
    {
        $container = $this->createContainerFromFile('default');

        $this->assertTrue($container->getDefinition(SerializerInterface::class)->isPublic());
        $this->assertTrue($container->getAlias('bdf_serializer')->isPublic());
    }

    public function testNormalizers()
    {
        $container = $this->createContainerFromFile('default', [], false);
        $container->getCompilerPassConfig()->setBeforeOptimizationPasses([new TestCaseAllPublicCompilerPass(), new SerializerNormalizerPass()]);
        $container->compile();

        $definition = $container->getDefinition('bdf_serializer.normalizer.loader');

        $normalizers = $definition->getArgument(0);
        $this->assertCount(3, $normalizers);
        $this->assertSame('bdf_serializer.normalizer.datetime', (string) $normalizers[0]);
        $this->assertSame('bdf_serializer.normalizer.traversable', (string) $normalizers[1]);
        $this->assertSame('bdf_serializer.normalizer.property', (string) $normalizers[2]);
    }

    public function testLoaders()
    {
        $container = $this->createContainerFromFile('default', [], false);
        $container->getCompilerPassConfig()->setBeforeOptimizationPasses([new TestCaseAllPublicCompilerPass(), new SerializerLoaderPass()]);
        $container->compile();

        $definition = $container->getDefinition('bdf_serializer.metadata_factory');

        $loaders = $definition->getArgument(0);
        $this->assertCount(2, $loaders);
        $this->assertSame('bdf_serializer.metadata.loader.static_method', (string) $loaders[0]);
        $this->assertSame('bdf_serializer.metadata.loader.annotation', (string) $loaders[1]);
        $this->assertSame(null, $definition->getArgument(1));
    }

    public function testServiceCache()
    {
        $container = $this->createContainerFromFile('with_cache', [], false);
        $container->getCompilerPassConfig()->setBeforeOptimizationPasses([new TestCaseAllPublicCompilerPass()]);
        $container->setDefinition('TestCache', (new Definition('TestCache'))->setPublic(true));
        $container->compile();

        $definition = $container->getDefinition('bdf_serializer.metadata_factory');
        $this->assertSame('TestCache', (string) $definition->getArgument(1));
    }

    public function testPoolCache()
    {
        $container = $this->createContainerFromFile('with_pool_cache', [], false);
        $container->getCompilerPassConfig()->setBeforeOptimizationPasses([new TestCaseAllPublicCompilerPass()]);
        $container->setDefinition('cache.app', (new Definition(ArrayAdapter::class))->setPublic(true));
        $container->compile();

        $definition = $container->getDefinition('bdf_serializer.metadata_factory');
        $this->assertSame('bdf_serializer.cache', (string) $definition->getArgument(1));

        $definition = $container->getDefinition('bdf_serializer.cache');
        $this->assertSame('cache.app', (string) $definition->getArgument(0));
    }

    /**
     * @return ContainerBuilder
     */
    protected function createContainer(array $data = [])
    {
        return new ContainerBuilder(new EnvPlaceholderParameterBag($data));
    }

    /**
     * @param string $file
     * @param array  $data
     * @param bool   $compile
     *
     * @return ContainerBuilder
     *
     * @throws \Exception
     */
    protected function createContainerFromFile($file, $data = [], $compile = true)
    {
        $container = $this->createContainer($data);
        $container->registerExtension(new BdfSerializerExtension());

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/Fixtures/config'));
        $loader->load($file.'.yml');

        if (!$compile) {
            return $container;
        }

        $container->compile();

        return $container;
    }
}

class TestCaseAllPublicCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if (false === strpos($id, 'bdf_serializer')) {
                continue;
            }

            $definition->setPublic(true);
        }

        foreach ($container->getAliases() as $id => $alias) {
            if (false === strpos($id, 'bdf_serializer')) {
                continue;
            }

            $alias->setPublic(true);
        }
    }
}
