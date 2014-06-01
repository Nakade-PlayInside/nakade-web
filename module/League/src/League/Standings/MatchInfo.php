<?php
namespace League\Standings;

use League\Statistics\Tiebreaker\TiebreakerFactory;
use League\Statistics\Games\GamesStatsFactory;

/**
 * Class MatchInfo
 *
 * @package League\Statistics
 */
class MatchInfo
{
    protected $players;
    protected $matches;
    protected $pointsForWin=1;
    protected $pointsForDraw=1;
    protected $firstTieBreak ='Hahn';
    protected $secondTieBreak='SODOS';
    protected $thirdTieBreak ='CUSS';

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
     * get the players
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        if (isset($data['players'])) {
            $this->players = $data['players'];
        }
        if (isset($data['matches'])) {
            $this->matches = $data['matches'];
        }
        if (isset($data['winPoints'])) {
            $this->pointsForWin = $data['winPoints'];
        }
        if (isset($data['drawPoints'])) {
            $this->pointsForDraw = $data['drawPoints'];
        }
        if (isset($data['tieBreaker1'])) {
            $this->firstTieBreak = $data['tieBreaker1'];
        }
        if (isset($data['tieBreaker2'])) {
            $this->secondTieBreak = $data['tieBreaker2'];
        }
        if (isset($data['tieBreaker3'])) {
            $this->thirdTieBreak = $data['tieBreaker3'];
        }
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

}

