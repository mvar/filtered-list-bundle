<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Data;

/**
 * Single filter data holder.
 */
class FilterData implements FilterDataInterface
{
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
     * @param array  $parameters
     * @param mixed  $value
     */
    public function __construct(array $parameters, $value)
    {
        $this->parameters = $parameters;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getType() : string
    {
        return $this->parameters['type'];
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias() : string
    {
        return $this->parameters['alias'];
    }

    /**
     * {@inheritdoc}
     */
    public function isActive() : bool
    {
        return $this->getValue() !== null && $this->getValue() !== '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameter() : string
    {
        return $this->parameters['request_parameter'];
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns parameters.
     *
     * @return array
     */
    protected function getParameters() : array
    {
        return $this->parameters;
    }
}
