<?php
namespace Nakade\Standings\Tiebreaker;

/**
 * Calculating the Sum of defeated Opponents Scores.
 * This tiebreaker is suited best as a third tiebreaker since the only
 * resulting difference is between successful players.
 *
 * @package Nakade\Standings\Tiebreaker
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

            if (!$match->hasResult() ||
               $match->getResult()->getResultType()->getId() == self::SUSPENDED ||
               $match->getResult()->getResultType()->getId() == self::DRAW
            ) {
               continue;
            }

            if ($match->getResult()->getWinner()->getId() == $playerId && $match->getBlack()->getId() == $playerId ) {
               $opponent = $match->getWhite()->getId();
               $sos += $this->calculatePointsByOpponent($opponent);
               continue;
            }

            if ($match->getResult()->getWinner()->getId() == $playerId && $match->getWhite()->getId() == $playerId) {
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
