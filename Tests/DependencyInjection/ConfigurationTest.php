<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Tests\DependencyInjection;

use MVar\FilteredListBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testConfiguration().
     *
     * @return array
     */
    public function getTestConfigurationData()
    {
        return [
            'no_config' => [
                [],
                [
                    'lists' => [],
                ],
            ],
            'single_list' => [
                [
                    'lists' => [
                        'foo' => [
                            'select' => 'p',
                            'from' => 'AppBundle:Player p',
                        ],
                    ],
                ],
                [
                    'lists' => [
                        'foo' => [
                            'select' => 'p',
                            'from' => 'AppBundle:Player p',
                            'entity_manager' => 'doctrine.orm.entity_manager',
                            'filters' => [], // Optional. In case you just want to list everything
                        ],
                    ],
                ],
            ],
            'match_filter' => [
                [
                    'filters' => [
                        'match' => [
                            'name' => 'p.name',
                        ],
                    ],
                ],
                [
                    'lists' => [],
                    'filters' => [
                        'match' => [
                            'name' => [
                                'field' => 'p.name',
                                'request_parameter' => 'name', // Defaults to filter name
                            ],
                        ],
                        'range' => [],
                        'choice' => [],
                        'sort' => [],
                        'pager' => [],
                    ],
                ],
            ],
            'pager_filter' => [
                [
                    'filters' => [
                        'pager' => [
                            'pager' => null,
                        ],
                    ],
                ],
                [
                    'lists' => [],
                    'filters' => [
                        'match' => [],
                        'range' => [],
                        'choice' => [],
                        'sort' => [],
                        'pager' => [
                            'pager' => [
                                'request_parameter' => 'page',
                                'items_per_page' => 10,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $config
     * @param array $expected
     *
     * @dataProvider getTestConfigurationData()
     */
    public function testConfiguration($config, $expected)
    {
        $processor = new Processor();
        $processedConfig = $processor->processConfiguration(new Configuration(), [$config]);
        $this->assertEquals($expected, $processedConfig);
    }
}
