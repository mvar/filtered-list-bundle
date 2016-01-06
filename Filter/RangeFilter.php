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
 * This class filters entities where field value is between given range.
 */
class RangeFilter extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getWhereSnippet(FilterDataInterface $filterData)
    {
        $values = explode(',', $filterData->getValue());
        $from = $this->escapeValue($values[0]);
        $to = isset($values[1]) ? $this->escapeValue($values[1]) : null;

        if ($from !== null && $to !== null) {
            return sprintf("%s BETWEEN %s AND %s", $this->getConfig()['field'], $from, $to);
        }

        if ($from !== null) {
            return sprintf("%s >= %s", $this->getConfig()['field'], $from);
        }

        return sprintf("%s <= %s", $this->getConfig()['field'], $to);
    }

    /**
     * Escaped given value
     *
     * @param mixed $value
     *
     * @return string|null
     */
    private function escapeValue($value)
    {
        if ($value == '') {
            return null;
        }

        if (is_numeric($value)) {
            return $value;
        }

        // TODO: escape special characters

        return "'$value'";
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'range';
    }
}
