<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\GameStats;


/**
 * Determine the number of won games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class WonGames extends GameStats implements GameStatsInterface
{

    /**
     * gets you the number a won games
     * @param int $playerId
     *
     * @return int
     */
    public function getNumberOfGames($playerId)
    {

        $count=0;
        foreach ($this->getMatches() as $match) {

            if (null === $match->getResultId() ||
                $match->getResultId() == RESULT::DRAW ||
                $match->getResultId() == RESULT::SUSPENDED ) {

                continue;
            }

            if ($match->getWinnerId()==$playerId) {
                $count++;
            }
        }
        return $count;
    }
}

