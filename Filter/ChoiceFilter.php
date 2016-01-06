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
 * This class filters entities where field value matches any of selected choices.
 */
class ChoiceFilter extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getWhereSnippet(FilterDataInterface $filterData)
    {
        $values = $filterData->getValue();

        $clauses = [];
        foreach ($values as $value) {
            $clauses[] = sprintf("%s = %s", $this->getConfig()['field'], $this->escapeValue($value));
        }

        return '(' . implode(' OR ', $clauses) . ')';
    }

    /**
     * Escapes given value
     *
     * @param string $value
     *
     * @return string
     */
    private function escapeValue($value)
    {
        // TODO: escape special chars
        return "'$value'";
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilterDataClass()
    {
        return 'MVar\FilteredListBundle\Filter\Data\ChoiceFilterData';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'choice';
    }
}
