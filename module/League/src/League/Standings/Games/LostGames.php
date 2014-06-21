<?php
namespace League\Standings\Games;

use League\Standings\Results as RESULT;
use League\Standings\GameStats;

/**
 * Class LostGames
 *
 * @package League\Standings\Games
 */
class LostGames extends GameStats implements GameStatsInterface
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
                $match->getResult()->getResultType()->getId() == RESULT::DRAW  ||
                $match->getResult()->getResultType()->getId() == RESULT::SUSPENDED ||
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

