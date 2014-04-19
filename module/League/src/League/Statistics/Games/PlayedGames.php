<?php
namespace League\Statistics\Games;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;

/**
 * Determine the number of played games of a player.
 * This is a singleton. You will receive an only instance by using the
 * static call.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PlayedGames extends AbstractGameStats implements GameStatsInterface
{

    /**
     * gets you the number a played games
     * @param int $playerId
     *
     * @return int
     */
    public function getNumberOfGames($playerId)
    {

        $count=0;
        foreach ($this->getMatches() as $match) {

            if (null === $match->getResultId() ||
               $match->getResultId()==RESULT::SUSPENDED) {
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

