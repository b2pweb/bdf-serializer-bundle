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

    public function testDenormalizationOptions()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'denormalization_options' => [
                    'dateFormat' => 'd/m/y',
                    'throws_on_accessor_error' => true,
                    'json_options' => 'JSON_BIGINT_AS_STRING',
                ],
            ],
        ]);

        $this->assertEquals([
            'denormalization_options' => [
                'dateFormat' => 'd/m/y',
                'throws_on_accessor_error' => true,
                'json_options' => JSON_BIGINT_AS_STRING,
            ],
        ], $config);

        $config = $processor->processConfiguration(new Configuration(), [
            [
                'denormalization_options' => [
                    'json_options' => [
                        'JSON_BIGINT_AS_STRING',
                        'JSON_INVALID_UTF8_IGNORE',
                        'JSON_OBJECT_AS_ARRAY',
                    ],
                ],
            ],
        ]);

        $this->assertEquals([
            'denormalization_options' => [
                'json_options' => JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_IGNORE | JSON_OBJECT_AS_ARRAY,
            ],
        ], $config);

        $config = $processor->processConfiguration(new Configuration(), [
            [
                'denormalization_options' => [
                    'json_options' => 'JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_IGNORE',
                ],
            ],
        ]);

        $this->assertEquals([
            'denormalization_options' => [
                'json_options' => JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_IGNORE,
            ],
        ], $config);
    }

    public function testNormalizationOptions()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'normalization_options' => [
                    'dateFormat' => 'd/m/y',
                    'null' => true,
                    'json_options' => 'JSON_PRETTY_PRINT',
                ],
            ],
        ]);

        $this->assertEquals([
            'normalization_options' => [
                'dateFormat' => 'd/m/y',
                'null' => true,
                'json_options' => JSON_PRETTY_PRINT,
            ],
        ], $config);

        $config = $processor->processConfiguration(new Configuration(), [
            [
                'normalization_options' => [
                    'json_options' => [
                        'JSON_PRETTY_PRINT',
                        'JSON_HEX_TAG',
                        'JSON_INVALID_UTF8_IGNORE',
                    ],
                ],
            ],
        ]);

        $this->assertEquals([
            'normalization_options' => [
                'json_options' => JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_INVALID_UTF8_IGNORE,
            ],
        ], $config);

        $config = $processor->processConfiguration(new Configuration(), [
            [
                'normalization_options' => [
                    'json_options' => 'JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_INVALID_UTF8_IGNORE',
                ],
            ],
        ]);

        $this->assertEquals([
            'normalization_options' => [
                'json_options' => JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_INVALID_UTF8_IGNORE,
            ],
        ], $config);
    }
}
