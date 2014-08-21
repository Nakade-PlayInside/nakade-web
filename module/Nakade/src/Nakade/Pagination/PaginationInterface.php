<?php
namespace Nakade\Pagination;

/**
 * Interface FormServiceInterface
 *
 * @package Nakade\Pagination
 */
interface PaginationInterface
{
    const ITEMS_PER_PAGE = 10;

    /**
     * @param int $page
     *
     * @return \Zend\Paginator\Paginator
     */
    public function getPagination($page);
}