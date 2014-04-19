<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\GameStats;

/**
 * Determine the amounts of draw games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class DrawGames extends GameStats implements GameStatsInterface
{

    /**
     * gets you the number a draw games
     * @param int $playerId
     *
     * @return int
     */
    public function getNumberOfGames($playerId)
    {

        $count=0;
        foreach ($this->getMatches() as $match) {

            if (null === $match->getResultId() ||
               $match->getResultId()!=RESULT::DRAW) {
                continue;
            }

            if ($match->getBlackId()==$playerId ||
                $match->getWhiteId()==$playerId ) {

                $count++;
            }

        }

       return $count;
    }

}

