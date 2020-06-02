<?php

namespace Bdf\SerializerBundle\Tests\DependencyInjection;

use Bdf\SerializerBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * ConfigurationTest
 */
class ConfigurationTest extends TestCase
{
    public function test_default_config()
    {
        $globalconfig = [
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$globalconfig]);

        $this->assertEquals([], $config);
    }

    public function test_cache_pool()
    {
        $globalconfig = [
            'cache' => [
                'pool' => 'ServiceID'
            ]
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$globalconfig]);

        $this->assertEquals($globalconfig, $config);
    }

    public function test_cache_service()
    {
        $globalconfig = [
            'cache' => [
                'service' => 'ServiceID'
            ]
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$globalconfig]);

        $this->assertEquals($globalconfig, $config);
    }
}
