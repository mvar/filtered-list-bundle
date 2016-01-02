<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle;

use Doctrine\ORM\EntityManagerInterface;
use MVar\FilteredListBundle\Filter\FilterDataInterface;
use MVar\FilteredListBundle\Filter\FilterInterface;
use MVar\FilteredListBundle\Pager\PagerFilterInterface;
use MVar\FilteredListBundle\Result\FilteredList;
use Symfony\Component\HttpFoundation\Request;

class ListManager
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    /**
     * @var PagerFilterInterface
     */
    private $pager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $select;

    /**
     * @var string
     */
    private $from;

    /**
     * Constructor.
     *
     * @param $entityManager
     * @param string $select
     * @param string $from
     */
    public function __construct(EntityManagerInterface $entityManager, $select, $from)
    {
        $this->filters = [];
        $this->entityManager = $entityManager;
        $this->select = $select;
        $this->from = $from;
    }

    /**
     * Adds filter.
     *
     * @param FilterInterface $filter
     * @param string          $alias
     */
    public function addFilter(FilterInterface $filter, $alias)
    {
        if ($filter instanceof PagerFilterInterface) {
            if ($this->pager !== null) {
                throw new \LogicException('Only single pager filter per manager can by used.');
            }

            $this->pager = $filter;

            return;
        }

        $this->filters[$alias] = $filter;
    }

    /**
     * Handles HTTP request and returns built list.
     *
     * @param Request $request
     *
     * @return FilteredList
     */
    public function handleRequest(Request $request)
    {
        $filterData = $this->initializeFilterData($request);

        $query = $this->buildQuery($filterData);

        $results = $this->getResults($query);

        // TODO: get choices


        return $this->buildFilteredList($results, $filterData);
    }

    // TODO: move all private stuff to separate class

    /**
     * Initializes filter data.
     *
     * @param Request $request
     *
     * @return FilterDataInterface[]
     */
    private function initializeFilterData(Request $request)
    {
        $filterData = [];

        foreach ($this->filters as $name => $filter) {
            $filterData[$name] = $filter->initializeData($request);
        }

        return $filterData;
    }

    /**
     * Builds result query.
     *
     * @param FilterDataInterface[] $filterData
     *
     * @return string
     */
    private function buildQuery($filterData)
    {
        $dql = sprintf('SELECT %s FROM %s', $this->select, $this->from);

        $whereClauses = [];
        foreach ($filterData as $filter) {
            if ($filter->isActive()) {
                $whereClauses[] = $this->filters[$filter->getAlias()]->getWhereSnippet($filter);
            }
        }

        if (count($whereClauses)) {
            $dql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        return $dql;
    }

    /**
     * Returns DQL snippet for LIMIT clause.
     *
     * @return string
     */
    private function getLimitSnippet()
    {
        if ($this->pager === null) {
            return '';
        }

        $pager = $this->pager->getPager();
        $offset = $pager->getItemsPerPage();
        $from = $pager->getPage() * $offset - $offset;

        return sprintf(' LIMIT %d, %d', $from, $offset);
    }

    /**
     * Executes query and fetches results.
     *
     * @param string $dql
     *
     * @return array
     */
    private function getResults($dql)
    {
        // TODO: add sort snippet

        $query = $this->entityManager->createQuery($dql . $this->getLimitSnippet());

        return $query->getResult();
    }

    /**
     * Builds filtered list.
     *
     * @param array                 $results
     * @param FilterDataInterface[] $filterData
     *
     * @return FilteredList
     */
    private function buildFilteredList($results, $filterData)
    {
        return new FilteredList($results, $filterData);
    }
}
