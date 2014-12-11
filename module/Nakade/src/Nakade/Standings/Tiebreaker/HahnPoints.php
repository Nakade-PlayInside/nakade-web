<?php
namespace Nakade\Standings\Tiebreaker;

/**
 * Calculating Hahn-Points. The Hahn points are referred by Prof. Hahn from
 * the Myongji University, Seoul - Korea.
 * A counted result below the offset consequently ends in points for both
 * players. While the looser will get the difference, the winner will receive
 * the board point added to the offset.
 * Results exceeding the offset, as well as resignation, forfeit will provide
 * the winner the maximum points.
 * The benefit of this tiebreaker are the advantage of close games for the
 * looser, while a total defeat will give a bonus to the winner.
 *
 * @package Nakade\Standings\Tiebreaker
 */
class HahnPoints extends TiebreakerStats
{
    const MAX_POINTS=40;
    const OFFSET_POINTS=20;

    /**
     * calculating the points
     *
     * @param int $playerId
     *
     * @return int
     */
    public function getTieBreaker($playerId)
    {

        $count=0;
        /* @var $match \Season\Entity\Match */
        foreach ($this->getMatches() as $match) {

            if (!$match->hasResult() ||
               $match->getResult()->getResultType()->getId() == self::SUSPENDED ||
               $match->getResult()->getResultType()->getId() == self::DRAW) {
               continue;
            }

            if ($match->getResult()->getResultType()->getId() == self::BYPOINTS &&
                $match->getResult()->getWinner()->getId() == $playerId ) {
               $count += $match->getResult()->getPoints() + self::OFFSET_POINTS;
               continue;
            }

            if ($match->getResult()->getResultType()->getId() == self::BYPOINTS &&
               $match->getResult()->getPoints() < self::OFFSET_POINTS  &&
               ($match->getBlack()->getId() == $playerId ||
                $match->getWhite()->getId() == $playerId )) {

               $count += self::OFFSET_POINTS - $match->getResult()->getPoints();
               continue;
            }

            if ($match->getResult()->getWinner()->getId() == $playerId ) {
               $count +=self::MAX_POINTS;
            }
        }
        return $count;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Hahn';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Hahn Points';
    }
}