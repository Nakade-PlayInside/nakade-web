<?php
namespace League\Standings\Games;

use League\Standings\Results as RESULT;
use League\Standings\GameStats;

/**
 * Class DrawGames
 *
 * @package League\Standings\Games
 */
class DrawGames extends GameStats implements GameStatsInterface
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

            if (is_null($match->getResult()) ||
               $match->getResult()->getId() != RESULT::DRAW) {
                continue;
            }

            if ($match->getBlack()->getId()==$playerId || $match->getWhite()->getId()==$playerId ) {
                $count++;
            }

        }

       return $count;
    }

}

