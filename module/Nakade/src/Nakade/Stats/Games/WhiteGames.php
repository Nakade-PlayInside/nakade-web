<?php
namespace Nakade\Stats\Games;

use Nakade\Stats\GameStats;
/**
 * Class WhiteGames
 *
 * @package Nakade\Stats\Games
 */
class WhiteGames extends GameStats
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
                $match->getResult()->getResultType()->getId() == self::SUSPENDED ) {
                continue;
            }

            if ($match->getWhite()->getId()==$playerId) {
                $count++;
            }
        }
        return $count;
    }
}

