<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Result;

use MVar\FilteredListBundle\Filter\FilterDataInterface;

class FilteredList implements \IteratorAggregate
{
    /**
     * @var array|\Iterator
     */
    private $results;

    /**
     * @var FilterDataInterface
     */
    private $filters;

    private $pager; // TODO: implement

    /**
     * Constructor.
     *
     * @param array|\Iterator       $results
     * @param FilterDataInterface[] $filters
     */
    public function __construct($results, $filters)
    {
        $this->results = $results;
        $this->filters = $filters;
    }

    /**
     * Returns list of filters data.
     *
     * @return FilterDataInterface
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Returns list of URL parameters for current state.
     *
     * @return array
     */
    public function getUrlParameters()
    {
        $parameters = [];

        foreach ($this->filters as $filter) {
            if ($filter->isActive()) {
                $parameters[$filter->getRequestParameter()] = $filter->getValue();
            }
        }

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if ($this->results instanceof \Traversable) {
            return $this->results;
        }

        if (is_array($this->results)) {
            return new \ArrayIterator($this->results);
        }

        throw new \Exception('Result must be an array or implement \Traversable interface.');
    }
}
