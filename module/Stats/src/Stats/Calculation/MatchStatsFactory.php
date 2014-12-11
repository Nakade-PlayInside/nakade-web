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
        $stats = new GamesStatsFactory($matches);
        $stats->setPlayerId($userId);

        $results = array(
            'total' => count($matches),
            'played' => $stats->getPoints(GamesStatsFactory::GAMES_PLAYED),
            'suspended' => $stats->getPoints(GamesStatsFactory::GAMES_SUSPENDED),
            'wins' => $stats->getPoints(GamesStatsFactory::GAMES_WON),
            'draws' => $stats->getPoints(GamesStatsFactory::GAMES_DRAW),
            'defeats' => $stats->getPoints(GamesStatsFactory::GAMES_LOST),
            'points' => $stats->getPoints(GamesStatsFactory::GAMES_BY_POINTS),
            'lostOnTime' => $stats->getPoints(GamesStatsFactory::GAMES_LOST_ON_TIME),
            'lostByForfeit' => $stats->getPoints(GamesStatsFactory::GAMES_LOST_BY_FORFEIT),
            'closeMatches' => $stats->getPoints(GamesStatsFactory::CLOSE_MATCHES),
            'closeWins' => $stats->getPoints(GamesStatsFactory::CLOSE_WINS),
            'black' => $stats->getPoints(GamesStatsFactory::GAMES_ON_BLACK),
            'white' => $stats->getPoints(GamesStatsFactory::GAMES_ON_WHITE),
            'winOnBlack' => $stats->getPoints(GamesStatsFactory::WIN_ON_BLACK),
            'winOnWhite' => $stats->getPoints(GamesStatsFactory::WIN_ON_WHITE),
            'defeatOnBlack' => $stats->getPoints(GamesStatsFactory::DEFEAT_ON_BLACK),
            'defeatOnWhite' => $stats->getPoints(GamesStatsFactory::DEFEAT_ON_WHITE),
            'drawOnBlack' => $stats->getPoints(GamesStatsFactory::DRAW_ON_BLACK),
            'drawOnWhite' => $stats->getPoints(GamesStatsFactory::DRAW_ON_WHITE),
            'closeDefeats' => $stats->getPoints(GamesStatsFactory::CLOSE_DEFEATS),
            'lostByResign' => $stats->getPoints(GamesStatsFactory::LOST_BY_RESIGN),
            'highWins' => $stats->getPoints(GamesStatsFactory::HIGH_WINS),
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