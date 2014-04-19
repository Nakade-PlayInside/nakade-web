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
class MatchStats
{
    private $gamesStatsFactory;
    private $tieBreakerFactory;
    private $players;
    private $matches;
    private $pointsForWin=1;
    private $pointsForDraw=0;
    private $firstTieBreak ='Hahn';
    private $secondTieBreak='SODOS';
    private $thirdTieBreak ='CUSS';

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
     * Expecting an array of rules with a leading underline it will
     * populate the rules, i.e. points for wins, draws and which
     * tiebreakers are taken into account
     *
     * @param array $rules
     */
    public function populateRules($rules)
    {
        foreach ($rules as $key => $value) {

            $method = 'set'. ucfirst(str_replace('_', '', $key));
            if (method_exists($this, $method) && isset($value)) {
               $this->$method($value);
            }
        }

    }

    /**
     * Set the points a player receives for winning
     *
     * @param int $points
     *
     * @return $this
     */
    public function setWinpoints($points)
    {
        $this->pointsForWin = $points;
        return $this;
    }

    /**
     * Get the points a player receives for winning
     * @return int
     */
    public function getWinpoints()
    {
        return $this->pointsForWin;
    }

    /**
     * Set the points a player receives for a draw
     *
     * @param int $points
     *
     * @return $this
     */
    public function setDrawpoints($points)
    {
        $this->pointsForDraw = $points;
        return $this;
    }

    /**
     * Get the points a player receives for a draw
     *
     * @return int
     */
    public function getDrawpoints()
    {
        return $this->pointsForDraw;
    }

    /**
     * Set the first tiebreaker
     *
     * @param string $name
     *
     * @return $this
     */
    public function setTiebreaker1($name)
    {
        $this->firstTieBreak = $name;
        return $this;
    }

    /**
     * Get the first tiebreaker
     *
     * @return string
     */
    public function getTiebreaker1()
    {
        return $this->firstTieBreak;
    }

    /**
     * Set the second tiebreaker
     *
     * @param string $name
     *
     * @return $this
     */
    public function setTiebreaker2($name)
    {
        $this->secondTieBreak = $name;
        return $this;
    }

    /**
     * Get the second tiebreaker
     * @return string
     */
    public function getTiebreaker2()
    {
        return $this->secondTieBreak;
    }

    /**
     * Set the third tiebreaker
     *
     * @param string $name
     *
     * @return $this
     */
    public function setTiebreaker3($name)
    {
        $this->thirdTieBreak = $name;
        return $this;
    }

    /**
     * Get the third tiebreaker
     * @return string
     */
    public function getTiebreaker3()
    {
        return $this->thirdTieBreak;
    }

    /**
     * set an array of match entites
     *
     * @param array $allMatches
     *
     * @return $this
     */
    public function setMatches($allMatches)
    {
        $this->matches = $allMatches;
        return $this;
    }

    /**
     * get an array of match entites
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * set the players array
     *
     * @param array $playersInLeague
     *
     * @return $this
     */
    public function setPlayers($playersInLeague)
    {
        $this->players = $playersInLeague;
        return $this;
    }

    /**
     * get the players
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
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
        return  $this->getGamesStatsFactory()
                     ->getPoints('won')  * $this->getWinPoints() +
                $this->getGamesStatsFactory()
                     ->getPoints('draw') * $this->getDrawPoints();
    }



}

