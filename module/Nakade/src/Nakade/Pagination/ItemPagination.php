<?php
namespace Nakade\Pagination;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

/**
 * Default pagination depending on database request
 *
 * @package Nakade\Pagination
 */
class ItemPagination implements PaginationInterface
{
    protected $paginator;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $adapter = $this->getAdapter(count($items));
        $this->paginator = new Paginator($adapter);
        $this->paginator->setDefaultItemCountPerPage(self::ITEMS_PER_PAGE);
    }

    protected function getAdapter($total)
    {
        $pageArray = range(1, $total);
        return new ArrayAdapter($pageArray);
    }

    /**
     * @param int $page
     *
     * @return Paginator
     */
    public function getPagination($page)
    {
        return $this->paginator->setCurrentPageNumber($page);
    }

    /**
     * @param int $page
     *
     * @return int
     */
    public function getOffset($page)
    {
        return self::ITEMS_PER_PAGE * ($page -1);
    }

}

