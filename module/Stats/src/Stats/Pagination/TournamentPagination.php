<?php
namespace Stats\Pagination;

use Nakade\Pagination\ItemPagination;
use Zend\Paginator\Paginator;;

/**
 * Class LeaguePagination
 *
 * @package League\Pagination
 */
class TournamentPagination extends ItemPagination
{
    const TOURNAMENT_PER_PAGE = 1;
    const TOURNAMENT_RANGE = 1;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $adapter = $this->getAdapter(count($items));
        $this->paginator = new Paginator($adapter);

        $this->paginator->setPageRange(self::TOURNAMENT_RANGE);
        $this->paginator->setDefaultItemCountPerPage(self::TOURNAMENT_PER_PAGE);
    }

}

