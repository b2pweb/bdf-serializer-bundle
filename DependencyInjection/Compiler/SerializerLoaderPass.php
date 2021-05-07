<?php

namespace Bdf\SerializerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * Registers all service tag as loader into the serializer
 */
class SerializerLoaderPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    private $service;
    private $loaderTag;

    public function __construct(string $service = 'bdf_serializer.metadata_factory', string $loaderTag = 'bdf_serializer.loader')
    {
        $this->service = $service;
        $this->loaderTag = $loaderTag;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->service)) {
            return;
        }

        if (!$normalizers = $this->findAndSortTaggedServices($this->loaderTag, $container)) {
            throw new RuntimeException(sprintf('You must tag at least one service as "%s" to use the "%s" service.', $this->loaderTag, $this->service));
        }

        $serializerDefinition = $container->getDefinition($this->service);
        $serializerDefinition->replaceArgument(0, $normalizers);
    }
}
