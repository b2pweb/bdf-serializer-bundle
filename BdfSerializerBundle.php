<?php

namespace Bdf\SerializerBundle;

use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerLoaderPass;
use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerNormalizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * SerializerBundle.
 *
 * @author Seb
 */
class BdfSerializerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SerializerNormalizerPass());
        $container->addCompilerPass(new SerializerLoaderPass());
    }
}
