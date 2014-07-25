<?php
namespace User\Pagination;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use League\Mapper\ResultMapper;


class UserPagination
{
    const ITEMS_PER_PAGE    = 10;
    const PAGE_RANGE = 10;
    private $repository;

    /**
     * @param ResultMapper $repository
     */
    public function __construct(ResultMapper $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @param int $current
     *
     * @return Paginator
     */
    public function getPagination($id, $current)
    {
        $pages = $this->getPages($id);
        $adapter = $this->getArrayAdapter($pages);

        $pagination = new Paginator($adapter);
        $pagination
            ->setCurrentPageNumber($current)
            ->setItemCountPerPage(self::ITEMS_PER_PAGE)
            ->setPageRange(self::PAGE_RANGE);

        return $pagination;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    private function getPages($id)
    {
        $noMatchDays = $this->getRepository()->getNoOfMatchDaysByLeague($id);
        return range(1, $noMatchDays);
    }

    /**
     * @param array $pages
     *
     * @return ArrayAdapter
     */
    private function getArrayAdapter(array $pages)
    {
        return new ArrayAdapter($pages);
    }

    /**
     * @return ResultMapper
     */
    public function getRepository()
    {
        return $this->repository;
    }

}

