<?php
namespace Nakade\Stats\Games;

use Nakade\Stats\GameStats;
/**
 * Class TimeLossGames
 *
 * @package Nakade\Stats\Games
 */
class TimeLossGames extends GameStats
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

            if (!$match->hasResult()  || $match->getResult()->getResultType()->getId() != self::ONTIME) {
                continue;
            }

            if ($match->getResult()->getWinner()->getId() != $playerId) {
                $count++;
            }

        }

       return $count;
    }

}

