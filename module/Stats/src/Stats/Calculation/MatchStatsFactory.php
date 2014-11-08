<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation;


use Nakade\Stats\GamesStatsFactory;
use Stats\Entity\MatchStats;

class MatchStatsFactory {

    private $matchStats;

    public function __construct(array $matches, $userId)
    {
        $matchStats = new GamesStatsFactory($matches);
        $matchStats->setPlayerId($userId);

        $results = array(
            'total'  => count($matches),
            'played'    => $matchStats->getPoints(GamesStatsFactory::GAMES_PLAYED),
            'suspended' => $matchStats->getPoints(GamesStatsFactory::GAMES_SUSPENDED),
            'wins'   => $matchStats->getPoints(GamesStatsFactory::GAMES_WON),
            'draws'  => $matchStats->getPoints(GamesStatsFactory::GAMES_DRAW),
            'loss'  => $matchStats->getPoints(GamesStatsFactory::GAMES_LOST),
            'points'  => $matchStats->getPoints(GamesStatsFactory::GAMES_BY_POINTS),
            'lostOnTime'  => $matchStats->getPoints(GamesStatsFactory::GAMES_LOST_ON_TIME),
            'lostByForfeit'  => $matchStats->getPoints(GamesStatsFactory::GAMES_LOST_BY_FORFEIT)
        );

        $this->matchStats = new MatchStats();
        $this->matchStats->populate($results);

    }

    /**
     * @return MatchStats
     */
    public function getMatchStats()
    {
        return $this->matchStats;
    }


}