<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter;

use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;

/**
 * This class filters entities where field value matches request parameter value.
 */
class MatchFilter extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getWhereSnippet(FilterDataInterface $filterData)
    {
        // TODO: escape value
        return sprintf("%s = '%s'", $this->getConfig()['field'], $filterData->getValue());
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'match';
    }
}
