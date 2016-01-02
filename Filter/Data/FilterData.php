<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Data;

use MVar\FilteredListBundle\Filter\FilterDataInterface;

class FilterData implements FilterDataInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string $alias
     * @param array  $parameters
     * @param mixed  $value
     */
    public function __construct($alias, array $parameters, $value)
    {
        $this->alias = $alias;
        $this->parameters = $parameters;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->parameters['type'];
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->getValue() !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameter()
    {
        return $this->parameters['request_parameter'];
    }

    /**
     * {@inheritdoc}
     */
    public function getField()
    {
        return $this->parameters['field'];
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}
