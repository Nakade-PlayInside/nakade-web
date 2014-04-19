<?php
namespace League\Statistics;

use League\Statistics\Tiebreaker\TiebreakerFactory;
use League\Statistics\Games\GamesStatsFactory;

/**
 * Determining the games stats for having a sorted table of
 * a league.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MatchStats extends MatchInfo
{
    private $gamesStatsFactory;
    private $tieBreakerFactory;

    /**
     * setter TiebreakerFactory
     *
     * @param TiebreakerFactory $factory
     *
     * @return $this
     */
    public function setTiebreakerFactory($factory)
    {
        $this->tieBreakerFactory = $factory;
        return $this;
    }

    /**
     * getter TiebreakerFactory
     * @return TiebreakerFactory
     */
    public function getTiebreakerFactory()
    {
        if (is_null($this->tieBreakerFactory)) {

            $this->tieBreakerFactory =
                new TiebreakerFactory($this->matches);
        }
        return $this->tieBreakerFactory;
    }

    /**
     * setter GamesStats Factory
     *
     * @param GamesStatsFactory $factory
     *
     * @return $this
     */
    public function setGamesStatsFactory($factory)
    {
        $this->gamesStatsFactory = $factory;
        return $this;
    }

    /**
     * getter GamesStats Factory
     * @return GamesStatsFactory
     */
    public function getGamesStatsFactory()
    {
        if (is_null($this->gamesStatsFactory)) {

            $this->gamesStatsFactory =
                new GamesStatsFactory($this->matches);
        }

        return $this->gamesStatsFactory;
    }


    /**
     * @return array
     */
    public function getMatchStats()
    {

        $players = $this->getPlayers();

        //putting the calculated stats into the players
        for ($i = 0; $i < count($players); ++$i) {
           $player =  $players[$i];
           $data = $this->getPlayersStats($player->getUid());
           $player->populate($data);
        }

        return $players;
    }

    private function getPlayersStats($playerId)
    {
        $this->getGamesStatsFactory()->setPlayerId($playerId);
        $this->getTiebreakerFactory()->setPlayerId($playerId);

        $results = array(
           'gamesPlayed'    => $this->getGamesStatsFactory()
                                    ->getPoints('played'),

           'gamesSuspended' => $this->getGamesStatsFactory()
                                    ->getPoints('suspended'),

           'gamesWin'       => $this->getGamesStatsFactory()
                                    ->getPoints('won'),

           'gamesDraw'      => $this->getGamesStatsFactory()
                                    ->getPoints('draw'),

           'gamesLost'      => $this->getGamesStatsFactory()
                                    ->getPoints('lost'),

           'gamesPoints'    => $this->getPoints(),

           'firstTiebreak'  => $this->getTiebreakerFactory()
                                    ->getPoints($this->getTiebreaker1()),

           'secondTiebreak' => $this->getTiebreakerFactory()
                                    ->getPoints($this->getTiebreaker2()),

           'thirdTiebreak' => $this->getTiebreakerFactory()
                                    ->getPoints($this->getTiebreaker3()),

        );

        return $results;
    }

    private function getPoints()
    {
        return  (int) $this->getGamesStatsFactory()->getPoints('won')  * (int) $this->getWinPoints() +
                (int) $this->getGamesStatsFactory()->getPoints('draw') * (int) $this->getDrawPoints();
    }



}

