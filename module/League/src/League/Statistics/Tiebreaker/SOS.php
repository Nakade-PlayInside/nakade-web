<?php
namespace League\Statistics\Tiebreaker;

use League\Statistics\Results as RESULT;
use League\Statistics\AbstractGameStats;
use League\Statistics\Games\WonGames;
use League\Statistics\Games\DrawGames;

/**
 * Calculating the Sum of Opponents Scores. This tiebreaker is almost intended
 * for tournaments. In a robin-around-league the SOS results in having
 * all players with the same score.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SOS extends AbstractGameStats implements TiebreakerInterface
{

    /**
    * @var AbstractGameStats
    */
    private static $instance =null;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->setName('SOS');
        $this->setDescription('Sum of Opponent Score');
    }

    /**
     * Singleton Pattern for preventing object inflation.
     * @return AbstractGameStats
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new SOS();
        }

        return self::$instance;
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
               $match->getResultId()==RESULT::SUSPENDED) {

               continue;
            }

            if ($match->getBlackId() == $playerId) {

               $opponent = $match->getWhiteId();
               $sos += $this->getNumberOfDrawGames($opponent);
               $sos += $this->getNumberOfWonGames($opponent);
               continue;
            }

            if ($match->getWhiteId() == $playerId) {

               $opponent = $match->getBlackId();
               $sos += $this->getNumberOfDrawGames($opponent);
               $sos += $this->getNumberOfWonGames($opponent);
               continue;
            }

        }

        return $sos;

    }

    protected function getNumberOfDrawGames($playerId)
    {
        $obj = DrawGames::getInstance();
        $obj->setMatches($this->getMatches());
        return $obj->getNumberOfGames($playerId);
    }

    protected function getNumberOfWonGames($playerId)
    {
        $obj = WonGames::getInstance();
        $obj->setMatches($this->getMatches());
        return $obj->getNumberOfGames($playerId);
    }

}
