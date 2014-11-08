<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MatchStats
 *
 * @package Stats\Entity
 */
class MatchStats
{
    private $matches;
    private $played;
    private $wins;
    private $draws;
    private $suspended;
    private $close;
    private $time;
    private $forfeit;
    private $loss;

    public function __construct($matches)
    {
        $this->played=$this->wins=$this->draws=$this->suspended=$this->close=$this->time=$this->forfeit=$this->loss=0;
        $this->matches=$matches;
    }

    /**
     * @param int $close
     */
    public function setClose($close)
    {
        $this->close = $close;
    }

    /**
     * @return int
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * @param int $draws
     */
    public function setDraws($draws)
    {
        $this->draws = $draws;
    }

    /**
     * @return int
     */
    public function getDraws()
    {
        return $this->draws;
    }

    /**
     * @param int $forfeit
     */
    public function setForfeit($forfeit)
    {
        $this->forfeit = $forfeit;
    }

    /**
     * @return int
     */
    public function getForfeit()
    {
        return $this->forfeit;
    }

    /**
     * @param int $loss
     */
    public function setLoss($loss)
    {
        $this->loss = $loss;
    }

    /**
     * @return int
     */
    public function getLoss()
    {
        return $this->loss;
    }

    /**
     * @param int $matches
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;
    }

    /**
     * @return mixed
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @param int $played
     */
    public function setPlayed($played)
    {
        $this->played = $played;
    }

    /**
     * @return int
     */
    public function getPlayed()
    {
        return $this->played;
    }


    /**
     * @param mixed $suspended
     */
    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
    }

    /**
     * @return int
     */
    public function getSuspended()
    {
        return $this->suspended;
    }

    /**
     * @param int $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param int $wins
     */
    public function setWins($wins)
    {
        $this->wins = $wins;
    }

    /**
     * @return int
     */
    public function getWins()
    {
        return $this->wins;
    }





}