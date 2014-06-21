<?php
namespace League\Pagination;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use League\Mapper\ResultMapper;

/**
 * Class MatchDayPagination
 *
 * @package League\Pagination
 */
class MatchDayPagination
{
    const ITEMS_PER_PAGE    = 1;
    const PAGE_RANGE = 7;
    private $repository;

    /**
     * @param ResultMapper $repository
     */
    public function __construct(ResultMapper $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $leagueId
     * @param int $current
     *
     * @return Paginator
     */
    public function getPagination($leagueId, $current)
    {
        $pages = $this->getPages($leagueId);
        $adapter = $this->getArrayAdapter($pages);

        $pagination = new Paginator($adapter);
        $pagination
            ->setCurrentPageNumber($current)
            ->setItemCountPerPage(self::ITEMS_PER_PAGE)
            ->setPageRange(self::PAGE_RANGE);

        return $pagination;
    }

    /**
     * @param int $leagueId
     *
     * @return array
     */
    private function getPages($leagueId)
    {
        $noMatchDays = $this->getRepository()->getNoOfMatchDaysByLeague($leagueId);
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

