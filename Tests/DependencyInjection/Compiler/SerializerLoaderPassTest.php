<?php

namespace Bdf\SerializerBundle\Tests\DependencyInjection\Compiler;

use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerLoaderPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SerializerLoaderPassTest extends TestCase
{
    public function testThrowExceptionWhenNoLoaders()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You must tag at least one service as "bdf_serializer.loader" to use the "bdf_serializer.metadata_factory" service');
        $container = new ContainerBuilder();
        $container->register('bdf_serializer.metadata_factory');

        $serializerPass = new SerializerLoaderPass();
        $serializerPass->process($container);
    }

    public function testServicesAreOrderedAccordingToPriority()
    {
        $container = new ContainerBuilder();

        $definition = $container->register('bdf_serializer.metadata_factory')->setArguments([null]);
        $container->register('n2')->addTag('bdf_serializer.loader', ['priority' => 100]);
        $container->register('n1')->addTag('bdf_serializer.loader', ['priority' => 200]);
        $container->register('n3')->addTag('bdf_serializer.loader');

        $serializerPass = new SerializerLoaderPass();
        $serializerPass->process($container);

        $expected = [
            new Reference('n1'),
            new Reference('n2'),
            new Reference('n3'),
        ];

        $this->assertEquals($expected, $definition->getArgument(0));
    }
}
