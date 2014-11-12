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
            'total' => count($matches),
            'played' => $matchStats->getPoints(GamesStatsFactory::GAMES_PLAYED),
            'suspended' => $matchStats->getPoints(GamesStatsFactory::GAMES_SUSPENDED),
            'wins' => $matchStats->getPoints(GamesStatsFactory::GAMES_WON),
            'draws' => $matchStats->getPoints(GamesStatsFactory::GAMES_DRAW),
            'defeats' => $matchStats->getPoints(GamesStatsFactory::GAMES_LOST),
            'points' => $matchStats->getPoints(GamesStatsFactory::GAMES_BY_POINTS),
            'lostOnTime' => $matchStats->getPoints(GamesStatsFactory::GAMES_LOST_ON_TIME),
            'lostByForfeit' => $matchStats->getPoints(GamesStatsFactory::GAMES_LOST_BY_FORFEIT),
            'closeMatches' => $matchStats->getPoints(GamesStatsFactory::CLOSE_MATCHES),
            'closeWins' => $matchStats->getPoints(GamesStatsFactory::CLOSE_WINS),
            'black' => $matchStats->getPoints(GamesStatsFactory::GAMES_ON_BLACK),
            'white' => $matchStats->getPoints(GamesStatsFactory::GAMES_ON_WHITE),
            'winOnBlack' => $matchStats->getPoints(GamesStatsFactory::WIN_ON_BLACK),
            'winOnWhite' => $matchStats->getPoints(GamesStatsFactory::WIN_ON_WHITE),
            'defeatOnBlack' => $matchStats->getPoints(GamesStatsFactory::DEFEAT_ON_BLACK),
            'defeatOnWhite' => $matchStats->getPoints(GamesStatsFactory::DEFEAT_ON_WHITE),
            'drawOnBlack' => $matchStats->getPoints(GamesStatsFactory::DRAW_ON_BLACK),
            'drawOnWhite' => $matchStats->getPoints(GamesStatsFactory::DRAW_ON_WHITE),
            'closeDefeats' => $matchStats->getPoints(GamesStatsFactory::CLOSE_DEFEATS),
            'lostByResign' => $matchStats->getPoints(GamesStatsFactory::LOST_BY_RESIGN),
            'highWins' => $matchStats->getPoints(GamesStatsFactory::HIGH_WINS),
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