<?php
namespace Nakade\Stats\Games;

use Nakade\Stats\GameStats;
/**
 * Class WonGames
 *
 * @package Nakade\Stats\Games
 */
class WonGames extends GameStats
{

    /**
     * @param int $playerId
     *
     * @return int
     */
    public function getNumberOfGames($playerId)
    {

        $count=0;
        /* @var $match \Season\Entity\Match */
        foreach ($this->getMatches() as $match) {

            if (!$match->hasResult() ||
                !$match->getResult()->hasWinner() ||
                $match->getResult()->getResultType()->getId() == self::DRAW ||
                $match->getResult()->getResultType()->getId() == self::SUSPENDED ) {
                continue;
            }

            if ($match->getResult()->getWinner()->getId()==$playerId) {
                $count++;
            }
        }
        return $count;
    }
}

