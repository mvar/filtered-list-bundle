<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter;

use MVar\FilteredListBundle\Filter\Data\FilterData;
use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Filter abstraction.
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var array
     */
    private $config;

    /**
     * Constructor.
     *
     * @param string $alias
     * @param array  $config
     */
    public function __construct(string $alias, array $config)
    {
        $this->alias = $alias;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeData(Request $request) : FilterDataInterface
    {
        $config = ['type' => $this->getType(), 'alias' => $this->alias];
        $config = array_merge($config, $this->config);
        unset($config['field']);

        $class = $this->getFilterDataClass();

        return new $class($config, $request->get($this->config['request_parameter']));
    }

    /**
     * Returns filter data class name.
     *
     * @return string
     */
    protected function getFilterDataClass() : string
    {
        return FilterData::class;
    }

    /**
     * Returns filter config.
     *
     * @return array
     */
    protected function getConfig() : array
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias() : string
    {
        return $this->alias;
    }
}
