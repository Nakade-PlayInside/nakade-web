<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;
use League\Statistics\GameStats;
use League\Statistics\Games\WonGames;
use League\Statistics\Games\DrawGames;

/**
 * Calculating the Sum of Opponents Scores. This tiebreaker is almost intended
 * for tournaments. In a robin-around-league the SOS results in having
 * all players with the same score.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SOS extends GameStats implements TiebreakerInterface
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
        /* @var $match \League\Entity\Match */
        foreach ($this->getMatches() as $match) {

            if (null === $match->getResultId() ||
               $match->getResultId()==RESULT::SUSPENDED) {

               continue;
            }

            if ($match->getBlackId() == $playerId) {

               $opponent = $match->getWhiteId();
               $sos += $this->calculatePointsByOpponent($opponent);
               continue;
            }

            if ($match->getWhiteId() == $playerId) {

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
        $obj = DrawGames::getInstance();
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
        $obj = WonGames::getInstance();
        $obj->setMatches($this->getMatches());
        return (int) $obj->getNumberOfGames($playerId);
    }

}
