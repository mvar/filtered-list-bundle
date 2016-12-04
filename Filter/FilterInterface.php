<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter;

use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
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
    public function initializeData(Request $request) : FilterDataInterface;

    /**
     * Returns filter alias unique per configured filter.
     *
     * @return string
     */
    public function getAlias() : string;

    /**
     * Returns filter type unique per filter class.
     *
     * @return string
     */
    public function getType() : string;
}
