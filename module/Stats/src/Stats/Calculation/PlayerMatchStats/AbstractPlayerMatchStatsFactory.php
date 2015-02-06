<?php
/**
 * Created by PhpStorm.
 * User: mumm
 * Date: 08.11.14
 * Time: 11:01
 */

namespace Stats\Calculation\PlayerMatchStats;


use Season\Entity\Match;

abstract class AbstractPlayerMatchStatsFactory
{
    private $wins;
    private $consecutiveWins;
    private $defeats;
    private $draws;
    private $played;

    /**
     * parent constructor
     */
    public function __construct()
    {
        $this->wins = new Wins();
        $this->defeats = new Defeats();
        $this->draws = new Draws();
        $this->played = new Played();
        $this->consecutiveWins = new ConsecutiveWins();
    }

    /**
     * @param Match $match
     *
     * @return bool
     */
    abstract public function addMatch(Match $match);

    /**
     * @return array
     */
    abstract public function getData();


    /**
     * @return Wins
     */
    protected  function getWins()
    {
        return $this->wins;
    }

    /**
     * @return Defeats
     */
    protected function getDefeats()
    {
        return $this->defeats;
    }

    /**
     * @return Draws
     */
    protected function getDraws()
    {
        return $this->draws;
    }

    /**
     * @return Played
     */
    protected function getPlayed()
    {
        return $this->played;
    }

    /**
     * @return ConsecutiveWins
     */
    protected  function getConsecutiveWins()
    {
        return $this->consecutiveWins;
    }


}