<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Result;

use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
use MVar\FilteredListBundle\Pager\Pager;

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

    /**
     * @var Pager|null
     */
    private $pager;

    /**
     * Constructor.
     *
     * @param array|\Iterator       $results
     * @param FilterDataInterface[] $filters
     * @param Pager                 $pager
     */
    public function __construct($results, $filters, $pager = null)
    {
        $this->results = $results;
        $this->filters = $filters;
        $this->pager = $pager;
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
     * Returns pager
     *
     * @return Pager|null
     */
    public function getPager()
    {
        return $this->pager;
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

    /**
     * Returns number of total results.
     *
     * @return int
     */
    public function getResultsCount()
    {
        return $this->pager !== null ? $this->pager->getResultsCount() : count($this->results);
    }
}
