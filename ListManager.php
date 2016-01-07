<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle;

use Doctrine\ORM\EntityManagerInterface;
use MVar\FilteredListBundle\Filter\Condition\ConditionFilterInterface;
use MVar\FilteredListBundle\Filter\FilterInterface;
use MVar\FilteredListBundle\Filter\Data\FilterDataInterface;
use MVar\FilteredListBundle\Filter\Pager\Pager;
use MVar\FilteredListBundle\Filter\Pager\PagerFilterInterface;
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

        if ($this->pager !== null) {
            $pagerData = $filterData[$this->pager->getAlias()];
            $resultsCount = $this->getResultsCount($query);
            $pager = $this->pager->getPager($pagerData, $resultsCount);
        } else {
            $pager = null;
        }

        $results = $this->getResults($query, $pager);

        // TODO: get choices


        return $this->buildFilteredList($results, $filterData, $pager);
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
     * Result example:
     *
     *     [
     *         'query' => 'SELECT p From AppBundle:Player p WHERE p.name = "name',
     *         'parameters' => [
     *             'name' => 'Joe',
     *         ],
     *     ]
     *
     * @param FilterDataInterface[] $filterData
     *
     * @return array
     */
    private function buildQuery($filterData)
    {
        $query = sprintf('SELECT %s FROM %s', $this->select, $this->from);

        $whereClauses = [];
        $parameters = [];
        foreach ($filterData as $filter) {
            if ($filter->isActive() && $this->filters[$filter->getAlias()] instanceof ConditionFilterInterface) {
                $snippet = $this->filters[$filter->getAlias()]->getDqlSnippet($filter);
                $whereClauses[] = $snippet['snippet'];
                $parameters[] = $snippet['parameters'];
            }
        }

        if (count($whereClauses)) {
            $query .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        return [
            'query' => $query,
            'parameters' => $parameters,
        ];
    }

    /**
     * Executes query and fetches results.
     *
     * @param array $query
     * @param Pager $pager
     *
     * @return array
     */
    private function getResults($query, $pager)
    {
        // TODO: add sort snippet

        $queryObject = $this->entityManager->createQuery($query['query']);
        $queryObject->setParameters($query['parameters']);

        if ($this->pager !== null) {
            $limit = $pager->getItemsPerPage();
            $queryObject->setMaxResults($pager->getItemsPerPage());
            $queryObject->setFirstResult($pager->getPage() * $limit - $limit);
        }

        return $queryObject->getResult();
    }

    /**
     * Gets total results count.
     *
     * @param array $query
     *
     * @return int
     */
    private function getResultsCount($query)
    {
        $queryObject = $this->entityManager->createQuery(
            // TODO: fix hardcoded "p.id"
            str_replace('SELECT ' . $this->select, 'SELECT COUNT(p.id) cnt', $query['query'])
        );
        $queryObject->setParameters($query['parameters']);

        return $queryObject->getSingleScalarResult();
    }

    /**
     * Builds filtered list.
     *
     * @param array                 $results
     * @param FilterDataInterface[] $filterData
     * @param Pager                 $pager
     *
     * @return FilteredList
     */
    private function buildFilteredList($results, $filterData, $pager)
    {
        return new FilteredList($results, $filterData, $pager);
    }
}
