<?php
namespace League\Pagination;

use Nakade\Pagination\ItemPagination;
use Zend\Paginator\Paginator;;

/**
 * Class LeaguePagination
 *
 * @package League\Pagination
 */
class LeaguePagination extends ItemPagination
{
    const LEAGUES_PER_PAGE = 1;
    const LEAGUES_RANGE = 3;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $adapter = $this->getAdapter(count($items));
        $this->paginator = new Paginator($adapter);

        $this->paginator->setPageRange(self::LEAGUES_RANGE);
        $this->paginator->setDefaultItemCountPerPage(self::LEAGUES_PER_PAGE);
    }

    /**
     * @param int $leagueNo
     *
     * @return int
     */
    public function getOffset($leagueNo)
    {
        return self::LEAGUES_PER_PAGE * ($leagueNo -1);
    }

}

