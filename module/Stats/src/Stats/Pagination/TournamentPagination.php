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

    private $items;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
        $adapter = $this->getAdapter(count($items));
        $this->paginator = new Paginator($adapter);

        $this->paginator->setPageRange(self::TOURNAMENT_RANGE);
        $this->paginator->setDefaultItemCountPerPage(self::TOURNAMENT_PER_PAGE);
    }

    /**
     * @param int $page
     *
     * @return \Season\Entity\League
     */
    public function getItemByPage($page)
    {
        /* @var $item \Season\Entity\League */
        $item = $this->items[$page-1];
        return $item;
    }

    /**
     * @param int $lid
     *
     * @return int
     */
    public function getCurrentPageByItemId($lid)
    {
        $i = 1;

        /* @var $item \Season\Entity\League */
        foreach ($this->items as $item) {
            if ($item->getId()==$lid) {
                return $i;
            }
            $i++;
        }
        return $i;
    }

    /**
     * @param int $lid
     *
     * @return Paginator
     */
    public function setCurrentPageByItemId($lid)
    {
       $page = $this->getCurrentPageByItemId($lid);
       return $this->getPagination($page);
    }

    /**
     * @return Paginator
     */
    public function getMyPagination()
    {
        return $this->paginator;
    }


}

