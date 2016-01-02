<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter;

use Symfony\Component\HttpFoundation\Request;

/**
 * This interface defines stateless class which handles single filter.
 */
interface FilterInterface
{
    /**
     * Returns initial filter data object.
     *
     * @param Request $request
     *
     * @return FilterDataInterface
     */
    public function initializeData(Request $request);

    /**
     * Returns snippet for WHERE clause.
     *
     * @param FilterDataInterface $filterData
     *
     * @return string
     */
    public function getWhereSnippet(FilterDataInterface $filterData);
}
