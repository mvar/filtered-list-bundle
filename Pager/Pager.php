<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Pager;

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
    public function __construct($page, $itemsPerPage, $resultsCount)
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
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns number of items per page.
     *
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * Returns number of results.
     *
     * @return int
     */
    public function getResultsCount()
    {
        return $this->resultsCount;
    }

    /**
     * Returns TRUE if next page is available, FALSE otherwise.
     *
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->resultsCount > $this->page * $this->itemsPerPage;
    }

    /**
     * Returns total count of pages.
     *
     * @return int
     */
    public function getTotalPages()
    {
        return ceil($this->resultsCount / $this->itemsPerPage);
    }
}
