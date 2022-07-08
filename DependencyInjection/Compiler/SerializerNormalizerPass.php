<?php

namespace Bdf\SerializerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * Registers all service tag as normalizer into the serializer.
 */
class SerializerNormalizerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    private $service;
    private $normalizerTag;

    public function __construct(string $service = 'bdf_serializer.normalizer.loader', string $normalizerTag = 'bdf_serializer.normalizer')
    {
        $this->service = $service;
        $this->normalizerTag = $normalizerTag;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->service)) {
            return;
        }

        if (!$normalizers = $this->findAndSortTaggedServices($this->normalizerTag, $container)) {
            throw new RuntimeException(sprintf('You must tag at least one service as "%s" to use the "%s" service.', $this->normalizerTag, $this->service));
        }

        $serializerDefinition = $container->getDefinition($this->service);
        $serializerDefinition->replaceArgument(0, $normalizers);
    }
}
