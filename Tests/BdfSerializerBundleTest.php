<?php

namespace Bdf\SerializerBundle\Tests\DependencyInjection;

use Bdf\SerializerBundle\BdfSerializerBundle;
use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerLoaderPass;
use Bdf\SerializerBundle\DependencyInjection\Compiler\SerializerNormalizerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * BdfSerializerBundleTest
 */
class BdfSerializerBundleTest extends TestCase
{
    public function test_default_config()
    {
        $builder = new ContainerBuilder();
        $bundle = new BdfSerializerBundle();
        $bundle->build($builder);

        $compilerPasses = $builder->getCompiler()->getPassConfig()->getPasses();
        $found = 0;

        foreach ($compilerPasses as $pass) {
            if ($pass instanceof SerializerLoaderPass || $pass instanceof SerializerNormalizerPass) {
                $found++;
            }
        }

        $this->assertSame(2, $found);
    }
}
