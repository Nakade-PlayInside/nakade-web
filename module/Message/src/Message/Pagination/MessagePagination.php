<?php
namespace Message\Pagination;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

/**
 * Class MessagePagination
 *
 * @package Message\Pagination
 */
class MessagePagination
{
    const ITEMS_PER_PAGE = 5;
    private $paginator;


    /**
     * @param $total
     */
    public function __construct($total)
    {
        $adapter = $this->getAdapter($total);
        $this->paginator = new Paginator($adapter);
        $this->paginator->setDefaultItemCountPerPage(self::ITEMS_PER_PAGE);
    }

    private function getAdapter($total)
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

}

