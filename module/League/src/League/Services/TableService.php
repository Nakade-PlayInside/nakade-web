<?php

namespace League\Services;

use League\Standings\MatchStats;
use League\Standings\Sorting\PlayerPosition;
use League\Standings\Sorting\SortingInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use League\Standings\Sorting\PlayerSorting as SORT;

/**
 * Class TableService
 *
 * @package League\Services
 */
class TableService implements FactoryInterface, SortingInterface
{


    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     */
    public function createService(ServiceLocatorInterface $services)
    {
        return $this;
    }

    /**
     * @param array  $matches
     * @param string $sort
     *
     * @return array
     */
    public function getPlayersTable(array $matches, $sort=self::BY_POINTS)
    {
        $info = new MatchStats($matches);
        $players = $info->getMatchStats();
        $sorting = SORT::getInstance();
        $sorting->sorting($players, $sort);
        $pos = new PlayerPosition($players, $sort);
        return $pos->getPosition();
    }

}

