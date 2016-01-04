<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Pager;

use MVar\FilteredListBundle\Filter\AbstractFilter;
use MVar\FilteredListBundle\Filter\FilterDataInterface;

class PagerFilter extends AbstractFilter implements PagerFilterInterface
{
    public function getWhereSnippet(FilterDataInterface $filterData)
    {
        // TODO: Implement getWhereSnippet() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getPager(FilterDataInterface $filterData, $resultsCount)
    {
        $page = $filterData->getValue();
        $page = $page > 1 ? $page : 1;

        return new Pager($page, $this->getConfig()['items_per_page'], $resultsCount);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'pager';
    }
}
