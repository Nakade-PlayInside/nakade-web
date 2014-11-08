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

    private $matches;
    private $stats;



    public function __construct(array $matches, $userId)
    {
        $this->matches=$matches;
        $matchStats = new GamesStatsFactory($this->matches);
        $matchStats->setPlayerId($userId);

        $results = array(
            'played'    => $matchStats->getPoints(GamesStatsFactory::GAMES_PLAYED),
            'suspended' => $matchStats->getPoints(GamesStatsFactory::GAMES_SUSPENDED),
            'wins'   => $matchStats->getPoints(GamesStatsFactory::GAMES_WON),
            'draws'  => $matchStats->getPoints(GamesStatsFactory::GAMES_DRAW),
            'loss'  => $matchStats->getPoints(GamesStatsFactory::GAMES_LOST)
        );


   //     $this->stats = new MatchStats(count($matches));
     //   $this->calculate();
    }

    /**
     * @return MatchStats
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @return mixed
     */
    private function calculate()
    {
        /* @var $match \Season\Entity\Match */
        foreach($this->matches as $match) {

          //  $match->getResult()->getResultType() == ResultInterface::SUSPENDED;
        }
    }




}