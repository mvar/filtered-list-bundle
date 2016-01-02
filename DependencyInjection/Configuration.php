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
            ->prototype('array')
                ->children()
                    ->scalarNode('name')->end()
                    ->scalarNode('field')
                        ->info('Entity field name.')
                    ->end()
                    ->scalarNode('request_parameter')    // TODO: get default value from filter "name"
                        ->info('Request parameter name.')
                    ->end()
                ->end();

        switch ($filter) {
            case 'pager':
                $children
                    ->children()
                        ->integerNode('items_per_page')
                        ->defaultValue(5) // TODO: make 10
                        ->end()
                    ->end();
                break;
        }

        return $node;
    }
}
