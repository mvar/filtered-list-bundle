<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Pager;

use MVar\FilteredListBundle\Filter\FilterInterface;

/**
 * This interface defines methods that pager filter must implement.
 */
interface PagerFilterInterface extends FilterInterface
{
    /**
     * Returns instance of pager.
     *
     * @return Pager
     */
    public function getPager();
}
