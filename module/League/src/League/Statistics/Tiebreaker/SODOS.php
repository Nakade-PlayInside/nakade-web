<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;

/**
 * Calculating the Sum of defeated Opponents Scores.
 * This tiebreaker is suited best as a third tiebreaker since the only
 * resulting difference is between successful players.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SODOS extends SOS
{

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
               $sos += $this->calculatePointsByOpponent($opponent);
               continue;
            }

            if ($match->getWinnerId()==$playerId &&
               $match->getWhiteId()==$playerId) {

               $opponent = $match->getBlackId();
               $sos += $this->calculatePointsByOpponent($opponent);
               continue;
            }

        }

        return $sos;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'SODOS';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Sum of defeated Opponents Score';
    }

}
