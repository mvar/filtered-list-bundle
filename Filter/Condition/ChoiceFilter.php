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
 * This class filters entities where field value matches any of selected choices.
 */
class ChoiceFilter extends AbstractFilter implements ConditionFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getWhereSnippet(FilterDataInterface $filterData)
    {
        $values = $filterData->getValue();

        $clauses = [];
        $parameters = [];
        foreach ($values as $value) {
            $parameter = Container::camelize($this->getConfig()['field'] . '_' . $value);
            $clauses[] = sprintf("%s = :%s", $this->getConfig()['field'], $parameter);
            $parameters[$parameter] = $value;
        }

        return [
            'snippet' => '(' . implode(' OR ', $clauses) . ')',
            'parameters' => $parameters,
        ];
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
