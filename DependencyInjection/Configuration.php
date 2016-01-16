<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mvar_filtered_list');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('lists')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')
                                ->info('Filtered list name')
                            ->end()
                            ->scalarNode('entity_manager')
                                ->defaultValue('doctrine.orm.entity_manager')
                            ->end()
                            ->scalarNode('select')
                                ->isRequired()
                                ->example('p')
                            ->end()
                            ->scalarNode('from')
                                ->isRequired()
                                ->example('AppBundle:Player p')
                            ->end()
                            ->arrayNode('filters')
                                ->defaultValue([])
                                ->prototype('scalar')->end()
                                ->info('Array of filters used by this list.')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        $this->addFiltersSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds "filters" configuration subtree.
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addFiltersSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('filters')
                    ->children()
                        ->append($this->buildFilterNode('match'))
                        ->append($this->buildFilterNode('range'))
                        ->append($this->buildFilterNode('choice'))
                        ->append($this->buildFilterNode('sort'))
                        ->append($this->buildFilterNode('pager'))
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Builds configuration node by given filter name.
     *
     * @param string $filter
     *
     * @return ArrayNodeDefinition
     */
    private function buildFilterNode($filter)
    {
        $node = new ArrayNodeDefinition($filter);

        $children = $node
            ->useAttributeAsKey('name')
            ->beforeNormalization()
                ->always(function ($a) {
                    foreach ($a as $name => $v) {
                        if ($name == 'pager') {
                            if (!isset($v['request_parameter'])) {
                                $a[$name]['request_parameter'] = 'page';
                            }
                            continue;
                        }
                        if (!is_array($v)) {
                            $a[$name] = [
                                'field' => $v,
                                'request_parameter' => $name,
                            ];
                        } elseif (!isset($v['request_parameter'])) {
                            $a[$name]['request_parameter'] = $name;
                        }
                    }
                    return $a;
                })
            ->end()
            ->prototype('array')
                ->children()
                    ->scalarNode('name')->end()
                    ->scalarNode('request_parameter')
                        ->info('Request parameter name.')
                    ->end()
                ->end();

        if ($filter != 'sort' && $filter != 'pager') {
            $children
                ->children()
                    ->scalarNode('field')
                        ->info('Entity field name.')
                        ->isRequired()
                    ->end()
                ->end();
        }

        switch ($filter) {
            case 'choice':
                $children
                    ->children()
                        ->arrayNode('choices')
                            ->useAttributeAsKey('value')
                            ->prototype('array')
                                ->beforeNormalization()
                                    ->ifString()->then(function ($v) { return ['name' => $v]; })
                                ->end()
                                ->children()
                                    ->scalarNode('value')->end()
                                    ->scalarNode('name')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end();
                break;
            case 'sort':
                $children
                    ->children()
                        ->arrayNode('choices')
                            ->useAttributeAsKey('value')
                            ->beforeNormalization()
                                ->always(function ($a) {
                                    foreach ($a as $name => $v) {
                                        if (is_string($v)) {
                                            $a[$name] = [
                                                'field' => $v,
                                                'name' => $name,
                                            ];
                                        } elseif (!isset($v['name'])) {
                                            $a[$name]['name'] = $name;
                                        }
                                    }
                                    return $a;
                                })
                            ->end()
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('value')->end()
                                    ->scalarNode('field')->isRequired()->end()
                                    ->enumNode('order')->values(['asc', 'desc'])->defaultValue('asc')->end()
                                    ->scalarNode('name')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end();
                break;
            case 'pager':
                $children
                    ->children()
                        ->integerNode('items_per_page')
                            ->defaultValue(10)
                        ->end()
                    ->end();
                break;
        }

        return $node;
    }
}
