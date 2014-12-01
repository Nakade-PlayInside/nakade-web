<?php
namespace Nakade\Standings\Tiebreaker;

use Nakade\Stats\Games;

/**
 * Calculating the Sum of Opponents Scores. This tiebreaker is almost intended
 * for tournaments. In a robin-around-league the SOS results in having
 * all players with the same score.
 *
 * @package Nakade\Standings\Tiebreaker
 */
class SOS extends TiebreakerStats
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

            if (!$match->getResult() || $match->getResult()->getResultType()->getId() == self::SUSPENDED) {
               continue;
            }

            if ($match->getBlack()->getId() == $playerId) {
               $opponent = $match->getWhite()->getId();
               $sos += $this->calculatePointsByOpponent($opponent);
               continue;
            }

            if ($match->getWhite()->getId() == $playerId) {
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
        return 'SOS';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Sum of Opponent Score';
    }

    /**
     * @param int $opponent
     *
     * @return int
     */
    protected function calculatePointsByOpponent($opponent)
    {
        return (int) $this->getNumberOfDrawGames($opponent) +
        (int) $this->getNumberOfWonGames($opponent);
    }

    /**
     * @param int $playerId
     *
     * @return int
     */
    protected function getNumberOfDrawGames($playerId)
    {
        $obj = Games\DrawGames::getInstance();
        $obj->setMatches($this->getMatches());
        return (int) $obj->getNumberOfGames($playerId);
    }

    /**
     * @param int $playerId
     *
     * @return int
     */
    protected function getNumberOfWonGames($playerId)
    {
        $obj = Games\WonGames::getInstance();
        $obj->setMatches($this->getMatches());
        return (int) $obj->getNumberOfGames($playerId);
    }

}
