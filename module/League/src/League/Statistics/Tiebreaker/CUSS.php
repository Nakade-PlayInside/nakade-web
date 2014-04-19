<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;
use League\Statistics\GameStats;


/**
 * Calculating the Cummulative Sum of Scores which is the
 * sum of a player's points after each round (matchday).
 * This can break fraud by loosing with intent.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class CUSS extends GameStats implements  TiebreakerInterface
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
        $wins=0;
        $sumOfPoints = array();

        /* @var $match \League\Entity\Match */
        foreach ($this->getMatches() as $match) {

            if (null === $match->getResultId() ||
               $match->getResultId() == RESULT::SUSPENDED) {
               continue;
            }

            if ($match->getWinnerId() == $playerId) {
                $wins += 1;
            }

            if ($match->getWhiteId() == $playerId || $match->getBlackId() == $playerId) {
                $sumOfPoints[] = $wins;
            }

        }

        return $this->getCummulativeSumOfPoints($sumOfPoints);

    }

    /**
     * @param array $sumOfPoints
     *
     * @return int
     */
    private function getCummulativeSumOfPoints(array $sumOfPoints)
    {
        $cuss=0;
        foreach ($sumOfPoints as $point) {
            $cuss += $point;
        }
        return $cuss;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CUSS';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Cummulative Sum of Scores';
    }
}

