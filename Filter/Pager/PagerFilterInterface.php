<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Pager;

use MVar\FilteredListBundle\Filter\FilterInterface;
use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;

/**
 * This interface defines methods that pager filter must implement.
 */
interface PagerFilterInterface extends FilterInterface
{
    /**
     * Returns instance of pager.
     *
     * @param FilterDataInterface $filterData
     * @param int                 $resultsCount
     *
     * @return Pager
     */
    public function getPager(FilterDataInterface $filterData, $resultsCount);
}
