<?php
namespace Nakade\Stats\Games;

use Nakade\Stats\GameStats;
/**
 * Class BlackGames
 *
 * @package Nakade\Stats\Games
 */
class BlackGames extends GameStats
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

            if ($match->getBlack()->getId()==$playerId) {
                $count++;
            }
        }
        return $count;
    }
}

