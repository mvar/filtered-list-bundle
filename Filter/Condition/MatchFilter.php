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
 * This class filters entities where field value matches request parameter value.
 */
class MatchFilter extends AbstractFilter implements ConditionFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getWhereSnippet(FilterDataInterface $filterData)
    {
        $parameter = Container::camelize($this->getConfig()['field']);

        return [
            'snippet' => sprintf("%s = :%s", $this->getConfig()['field'], $parameter),
            'parameters' => [
                $parameter => $filterData->getValue(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getType() : string
    {
        return 'match';
    }
}
