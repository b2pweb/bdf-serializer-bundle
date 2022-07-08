<?php

namespace Bdf\SerializerBundle\Tests\DependencyInjection;

use Bdf\SerializerBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * ConfigurationTest.
 */
class ConfigurationTest extends TestCase
{
    public function testDefaultConfig()
    {
        $globalconfig = [
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$globalconfig]);

        $this->assertEquals([], $config);
    }

    public function testCachePool()
    {
        $globalconfig = [
            'cache' => [
                'pool' => 'ServiceID',
            ],
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$globalconfig]);

        $this->assertEquals($globalconfig, $config);
    }

    public function testCacheService()
    {
        $globalconfig = [
            'cache' => [
                'service' => 'ServiceID',
            ],
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$globalconfig]);

        $this->assertEquals($globalconfig, $config);
    }
}
