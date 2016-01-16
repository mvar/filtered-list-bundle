<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Condition;

use MVar\FilteredListBundle\Filter\AbstractFilter;
use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * This class filters entities where field value is between given range.
 */
class RangeFilter extends AbstractFilter implements ConditionFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getWhereSnippet(FilterDataInterface $filterData)
    {
        $values = explode(',', $filterData->getValue());
        $from = $values[0];
        $to = isset($values[1]) ? $values[1] : null;

        $parameters = [];
        $parameterFrom = Container::camelize($this->getConfig()['field'] . '_from');
        $parameterTo = Container::camelize($this->getConfig()['field'] . '_to');

        if ($from !== null && $to !== null) {
            $parameters[$parameterFrom] = $from;
            $parameters[$parameterTo] = $to;
            $snippet = sprintf("%s BETWEEN :%s AND :%s", $this->getConfig()['field'], $parameterFrom, $parameterTo);
        } elseif ($from !== null) {
            $parameters[$parameterFrom] = $from;
            $snippet = sprintf("%s >= :%s", $this->getConfig()['field'], $parameterFrom);
        } else {
            $parameters[$parameterTo] = $to;
            $snippet = sprintf("%s <= :%s", $this->getConfig()['field'], $parameterTo);
        }

        return [
            'snippet' => $snippet,
            'parameters' => $parameters,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'range';
    }
}
