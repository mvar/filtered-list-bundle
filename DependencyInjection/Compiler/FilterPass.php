<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiles custom filters.
 */
class FilterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $filters = $this->getFilters($container);

        foreach ($container->findTaggedServiceIds('mvar_filtered_list.filter_consumer') as $consumer => $tags) {
            $definition = $container->findDefinition($consumer);

            foreach ($tags as $tag) {
                $alias = $tag['alias'];
                $definition->addMethodCall('addFilter', [new Reference($filters[$alias]), $alias]);
            }
        }
    }

    /**
     * Returns list of available filters where key is filter alias and value is service ID.
     *
     * @param ContainerBuilder $container
     *
     * @return array
     */
    private function getFilters(ContainerBuilder $container)
    {
        $filters = [];

        foreach ($container->findTaggedServiceIds('mvar_filtered_list.filter') as $filter => $tags) {
            foreach ($tags as $tag) {
                // TODO: validate if alias is configured
                $filters[$tag['alias']] = $filter;
            }
        }

        return $filters;
    }
}
