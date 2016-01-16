<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Sort;

use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
use MVar\FilteredListBundle\Filter\FilterInterface;

/**
 * Interface for filters that influences ORDER BY clause of a DQL query.
 */
interface SortFilterInterface extends FilterInterface
{
    /**
     * Returns DQL snippet for ORDER BY clause.
     *
     * @param FilterDataInterface $filterData
     *
     * @return string
     */
    public function getOrderBySnippet(FilterDataInterface $filterData);
}
