<?php
namespace League\Standings\Tiebreaker;

use League\Standings\Results as RESULT;
use Nakade\Stats\GameStats;


/**
 * Calculating the Cumulative Sum of Scores which is the
 * sum of a player's points after each round (match day).
 * This can break fraud by loosing with intent.
 *
 * @package League\Standings\Tiebreaker
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

        /* @var $match \Season\Entity\Match */
        foreach ($this->getMatches() as $match) {

            if (!$match->hasResult() || $match->getResult()->getResultType()->getId() == RESULT::SUSPENDED) {
               continue;
            }

            if ($match->getResult()->getWinner()->getId() == $playerId) {
                $wins += 1;
            }

            if ($match->getWhite()->getId() == $playerId || $match->getBlack()->getId() == $playerId) {
                $sumOfPoints[] = $wins;
            }

        }
        return $this->getCumulativeSumOfPoints($sumOfPoints);

    }

    /**
     * @param array $sumOfPoints
     *
     * @return int
     */
    private function getCumulativeSumOfPoints(array $sumOfPoints)
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
        return 'Cumulative Sum of Scores';
    }
}

