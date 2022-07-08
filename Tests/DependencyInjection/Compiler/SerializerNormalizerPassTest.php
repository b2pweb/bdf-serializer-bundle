<?php

namespace Bdf\SerializerBundle\Tests\DependencyInjection\Compiler;

use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerNormalizerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SerializerNormalizerPassTest extends TestCase
{
    public function testThrowExceptionWhenNoNormalizers()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You must tag at least one service as "bdf_serializer.normalizer" to use the "bdf_serializer.normalizer.loader" service');
        $container = new ContainerBuilder();
        $container->register('bdf_serializer.normalizer.loader');

        $serializerPass = new SerializerNormalizerPass();
        $serializerPass->process($container);
    }

    public function testServicesAreOrderedAccordingToPriority()
    {
        $container = new ContainerBuilder();

        $definition = $container->register('bdf_serializer.normalizer.loader')->setArguments([null]);
        $container->register('n2')->addTag('bdf_serializer.normalizer', ['priority' => 100]);
        $container->register('n1')->addTag('bdf_serializer.normalizer', ['priority' => 200]);
        $container->register('n3')->addTag('bdf_serializer.normalizer');

        $serializerPass = new SerializerNormalizerPass();
        $serializerPass->process($container);

        $expected = [
            new Reference('n1'),
            new Reference('n2'),
            new Reference('n3'),
        ];

        $this->assertEquals($expected, $definition->getArgument(0));
    }
}
