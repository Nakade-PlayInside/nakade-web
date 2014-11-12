<?php
namespace Nakade\Stats\Games;

use Nakade\Stats\GameStats;
/**
 * Class CloseMatches
 *
 * @package Nakade\Stats\Games
 */
class CloseWins extends GameStats
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
                $match->getResult()->getResultType()->getId() != self::BYPOINTS ||
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

