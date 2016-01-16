<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Condition;

use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
use MVar\FilteredListBundle\Filter\FilterInterface;

/**
 * Interface for filters that influences WHERE clause of a DQL query.
 */
interface ConditionFilterInterface extends FilterInterface
{
    /**
     * Returns DQL snippet and parameters for WHERE clause. This method is only called when filter is active.
     *
     * Example output:
     *
     *     [
     *         'snippet' => 'p.name = :name',
     *         'parameters' => [
     *             'name' => 'Joe',
     *         ],
     *     ]
     *
     * @param FilterDataInterface $filterData
     *
     * @return array
     */
    public function getWhereSnippet(FilterDataInterface $filterData);
}
