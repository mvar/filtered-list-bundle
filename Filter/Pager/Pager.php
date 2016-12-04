<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Pager;

/**
 * Pager data holder.
 */
class Pager
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $itemsPerPage;

    /**
     * @var int
     */
    private $resultsCount;

    /**
     * Constructor.
     *
     * @param int $page
     * @param int $itemsPerPage
     * @param int $resultsCount
     */
    public function __construct(int $page, int $itemsPerPage, int $resultsCount)
    {
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
        $this->resultsCount = $resultsCount;
    }

    /**
     * Returns current page number.
     *
     * @return int
     */
    public function getPage() : int
    {
        return $this->page;
    }

    /**
     * Returns number of items per page.
     *
     * @return int
     */
    public function getItemsPerPage() : int
    {
        return $this->itemsPerPage;
    }

    /**
     * Returns number of results.
     *
     * @return int
     */
    public function getResultsCount() : int
    {
        return $this->resultsCount;
    }

    /**
     * Returns TRUE if next page is available, FALSE otherwise.
     *
     * @return bool
     */
    public function hasNextPage() : bool
    {
        return $this->resultsCount > $this->page * $this->itemsPerPage;
    }

    /**
     * Returns total count of pages.
     *
     * @return int
     */
    public function getTotalPages() : int
    {
        if ($this->resultsCount < 1) {
            return 0;
        }

        return ceil($this->resultsCount / $this->itemsPerPage);
    }
}
