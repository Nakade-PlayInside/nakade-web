<?php
namespace Nakade\Stats\Games;

use Nakade\Stats\GameStats;
/**
 * Class BlackDrawGames
 *
 * @package Nakade\Stats\Games
 */
class BlackDrawGames extends GameStats
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
                $match->getResult()->getResultType()->getId() != self::DRAW) {
                continue;
            }

            if ($match->getBlack()->getId()==$playerId) {
                $count++;
            }
        }
        return $count;
    }
}

