<?php
namespace League\Standings\Games;

use League\Standings\Results as RESULT;
use League\Standings\GameStats;


/**
 * Class WonGames
 *
 * @package League\Standings\Games
 */
class WonGames extends GameStats implements GameStatsInterface
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
                !$match->getResult()->hasWinner() ||
                $match->getResult()->getResultType()->getId() == RESULT::DRAW ||
                $match->getResult()->getResultType()->getId() == RESULT::SUSPENDED ) {
                continue;
            }

            if ($match->getResult()->getWinner()->getId()==$playerId) {
                $count++;
            }
        }
        return $count;
    }
}

