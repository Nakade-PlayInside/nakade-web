<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;
use League\Statistics\Games\WonGames;
use League\Statistics\Games\DrawGames;

/**
 * Calculating the Sum of defeated Opponents Scores.
 * This tiebreaker is suited best as a third tiebreaker since the only
 * resulting difference is between successful players.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SODOS extends AbstractGameStats implements TiebreakerInterface
{

    /**
     * constructor
     */
    public function __construct()
    {
        $this->setName('SODOS');
        $this->setDescription('Sum of defeated Opponents Score');
    }

    /**
     * calculating the points
     *
     * @param int $playerId
     *
     * @return int
     */
    public function getTieBreaker($playerId)
    {

        $sos=0;
        foreach ($this->getMatches() as $match) {


            if (null === $match->getResultId() ||
               $match->getResultId() == RESULT::SUSPENDED ||
               $match->getResultId() == RESULT::DRAW
            ) {
               continue;
            }

            if ($match->getWinnerId()==$playerId &&
               $match->getBlackId()==$playerId ) {

               $opponent = $match->getWhiteId();
               $sos += $this->getNumberOfDrawGames($opponent);
               $sos += $this->getNumberOfWonGames($opponent);
               continue;
            }

            if ($match->getWinnerId()==$playerId &&
               $match->getWhiteId()==$playerId) {

               $opponent = $match->getBlackId();
               $sos += $this->getNumberOfDrawGames($opponent);
               $sos += $this->getNumberOfWonGames($opponent);
               continue;
            }

        }

        return $sos;

    }

    private function getNumberOfDrawGames($playerId)
    {
        $obj = DrawGames::getInstance();
        $obj->setMatches($this->getMatches());
        return $obj->getNumberOfGames($playerId);
    }

    private function getNumberOfWonGames($playerId)
    {
        $obj = WonGames::getInstance();
        $obj->setMatches($this->getMatches());
        return $obj->getNumberOfGames($playerId);
    }

}
