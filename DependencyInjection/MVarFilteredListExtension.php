<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages bundle configuration.
 */
class MVarFilteredListExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if (isset($config['lists'])) {
            $this->initializeLists($container, $config['lists']);
        }

        if (isset($config['filters'])) {
            $this->initializeFilters($container, $config['filters']);
        }
    }

    /**
     * Defines list services.
     *
     * @param ContainerBuilder $container
     * @param array            $lists
     */
    private function initializeLists(ContainerBuilder $container, array $lists)
    {
        foreach ($lists as $name => $config) {
            $definition = new Definition(
                'MVar\FilteredListBundle\ListManager',
                [
                    new Reference($config['entity_manager']),
                    $config['select'],
                    $config['from'],
                ]
            );

            foreach ($config['filters'] as $filter) {
                $definition->addTag('mvar_filtered_list.filter_consumer', ['alias' => $filter]);
            }

            $container->setDefinition('mvar_filtered_list.list.' . $name, $definition);
        }
    }

    /**
     * Defines filter services.
     *
     * @param ContainerBuilder $container
     * @param array            $filters
     */
    private function initializeFilters(ContainerBuilder $container, array $filters)
    {
        foreach ($filters as $filter => $configurations) {
            foreach ($configurations as $name => $config) {
                $definition = new Definition(
                    $this->getFilterClass($filter),
                    [
                        $name,
                        $config,
                    ]
                );

                $definition->addTag('mvar_filtered_list.filter', ['alias' => $name]);

                // TODO: if such filter exists throw exception
                $container->setDefinition('mvar_filtered_list.filter.' . $name, $definition);
            }
        }
    }

    /**
     * Returns filter class name by filter type.
     *
     * @param string $filter
     *
     * @return string
     */
    private function getFilterClass($filter)
    {
        switch ($filter) {
            case 'match';
                return 'MVar\FilteredListBundle\Filter\Condition\MatchFilter';
            case 'range';
                return 'MVar\FilteredListBundle\Filter\Condition\RangeFilter';
            case 'choice';
                return 'MVar\FilteredListBundle\Filter\Condition\ChoiceFilter';
            case 'sort';
                return 'MVar\FilteredListBundle\Filter\Sort\SortFilter';
            case 'pager';
                return 'MVar\FilteredListBundle\Filter\Pager\PagerFilter';
        }

        throw new \InvalidArgumentException(sprintf('Unknown filter type "%s".', $filter));
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'mvar_filtered_list';
    }
}
