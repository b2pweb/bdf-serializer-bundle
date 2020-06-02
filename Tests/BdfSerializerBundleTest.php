<?php

namespace Bdf\SerializerBundle\Tests\DependencyInjection;

use Bdf\SerializerBundle\BdfSerializerBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * BdfSerializerBundleTest
 */
class BdfSerializerBundleTest extends TestCase
{
    public function test_default_config()
    {
        $builder = $this->createMock(ContainerBuilder::class);

        $bundle = new BdfSerializerBundle();

        $this->assertNull($bundle->build($builder));
    }
}
