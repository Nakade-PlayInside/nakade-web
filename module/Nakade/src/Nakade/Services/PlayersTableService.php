<?php

namespace Nakade\Services;

use Nakade\Standings\Sorting\PlayerPosition;
use Nakade\Standings\Sorting\PlayerSorting as SORT;
use Nakade\Standings\Sorting\SortingInterface;
use Nakade\Standings\MatchStats;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TableStandingsService
 * calculate and provide standings and position of all players in a league
 *
 * @package Nakade\Services
 */
class PlayersTableService implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Nakade\Services\PlayersTableService
     */
    public function createService(ServiceLocatorInterface $services)
    {

        //$config  = $services->get('config');
        return $this;
    }

    /**
     * @param array $matches
     * @param string $sort
     * @return array
     */
    public function getTable(array $matches, $sort=SortingInterface::BY_POINTS)
    {
        //this is an extra service
        $stats = new MatchStats($matches);
        $players = $stats->getMatchStats();

        //just sorting
        $sorting = SORT::getInstance();
        $sorting->sorting($players, $sort);

        //evaluate player's position in table
        $standings = PlayerPosition::getInstance();
        return $standings->getStandings($players, $sort);
    }
}
