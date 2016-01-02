<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle;

use MVar\FilteredListBundle\DependencyInjection\Compiler\FilterPass;
use MVar\FilteredListBundle\DependencyInjection\MVarFilteredListExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle.
 */
class MVarFilteredListBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FilterPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new MVarFilteredListExtension();
    }
}
