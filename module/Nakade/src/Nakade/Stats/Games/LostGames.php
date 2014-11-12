<?php
namespace Nakade\Stats\Games;

use Nakade\Stats\GameStats;
/**
 * Class LostGames
 *
 * @package Nakade\Stats\Games
 */
class LostGames extends GameStats
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
                $match->getResult()->getResultType()->getId() == self::DRAW  ||
                $match->getResult()->getResultType()->getId() == self::SUSPENDED ||
                $match->getResult()->getWinner()->getId() == $playerId
            ) {
                continue;
            }

            if ($match->getBlack()->getId() == $playerId || $match->getWhite()->getId() == $playerId ) {
                $count++;
            }
        }
        return $count;
    }


}

