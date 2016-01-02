<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter;

use MVar\FilteredListBundle\Filter\Data\FilterData;
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
    public function __construct($alias, array $config)
    {
        $this->alias = $alias;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeData(Request $request)
    {
        return new FilterData($this->alias, $this->config, $request->get($this->config['request_parameter']));
    }

    /**
     * Returns filter config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return $this->config;
    }
}
