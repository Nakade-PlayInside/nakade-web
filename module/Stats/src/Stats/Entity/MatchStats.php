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
    private $total;
    private $played;
    private $wins;
    private $draws;
    private $suspended;
    private $points;
    private $lostOnTime;
    private $lostByForfeit;
    private $loss;

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
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
     * @param int $lostByForfeit
     */
    public function setLostByForfeit($lostByForfeit)
    {
        $this->lostByForfeit = $lostByForfeit;
    }

    /**
     * @return int
     */
    public function getLostByForfeit()
    {
        return $this->lostByForfeit;
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
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
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
     * @param int $lostOnTime
     */
    public function setLostOnTime($lostOnTime)
    {
        $this->lostOnTime = $lostOnTime;
    }

    /**
     * @return int
     */
    public function getLostOnTime()
    {
        return $this->lostOnTime;
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

    public function populate($data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }



}