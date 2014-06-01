<?php
namespace League\Standings\Tiebreaker;

use League\Standings\Results as RESULT;

/**
 * Calculating the Sum of defeated Opponents Scores.
 * This tiebreaker is suited best as a third tiebreaker since the only
 * resulting difference is between successful players.
 *
 * @package League\Standings\Tiebreaker
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
        /* @var $match \Season\Entity\Match */
        foreach ($this->getMatches() as $match) {

            if (is_null($match->getResult()) ||
               $match->getResult()->getId() == RESULT::SUSPENDED ||
               $match->getResult()->getId() == RESULT::DRAW
            ) {
               continue;
            }

            if ($match->getWinner()->getId() == $playerId && $match->getBlack()->getId() == $playerId ) {
               $opponent = $match->getWhite()->getId();
               $sos += $this->calculatePointsByOpponent($opponent);
               continue;
            }

            if ($match->getWinner()->getId() == $playerId && $match->getWhite()->getId() == $playerId) {
               $opponent = $match->getBlack()->getId();
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
