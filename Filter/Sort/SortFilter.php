<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Sort;

use MVar\FilteredListBundle\Filter\AbstractFilter;
use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
use MVar\FilteredListBundle\Filter\Data\SingleChoiceFilterData;

/**
 * This filter generates snippet for results ordering.
 */
class SortFilter extends AbstractFilter implements SortFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrderBySnippet(FilterDataInterface $filterData)
    {
        /** @var SingleChoiceFilterData $filterData */
        $value = $filterData->getValue();
        $choices = $filterData->getChoices();

        if (is_scalar($value) && isset($choices[$value])) {
            return sprintf('%s %s', $choices[$value]['field'], strtoupper($choices[$value]['order']));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilterDataClass()
    {
        return SingleChoiceFilterData::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'sort';
    }
}
