<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BasicMatchStats
 *
 * @package Stats\Entity
 */
abstract class AbstractMatchStats implements MatchStatsInterface
{
    protected $total;
    protected $played;
    protected $wins;
    protected $draws;
    protected $defeats;
    protected $closeWins;
    protected $closeMatches;
    protected $black;
    protected $white;
    protected $blackWin;
    protected $whiteWin;
    protected $blackDefeat;
    protected $whiteDefeat;
    protected $blackDraw;
    protected $whiteDraw;
    protected $suspended;
    protected $lostOnTime;
    protected $lostByForfeit;

    /**
     * @param int $blackDraw
     */
    public function setDrawOnBlack($blackDraw)
    {
        $this->blackDraw = $blackDraw;
    }

    /**
     * @return int
     */
    public function getDrawOnBlack()
    {
        return $this->blackDraw;
    }

    /**
     * @param int $whiteDraw
     */
    public function setDrawOnWhite($whiteDraw)
    {
        $this->whiteDraw = $whiteDraw;
    }

    /**
     * @return int
     */
    public function getDrawOnWhite()
    {
        return $this->whiteDraw;
    }

    /**
     * @param int $blackDefeat
     */
    public function setDefeatOnBlack($blackDefeat)
    {
        $this->blackDefeat = $blackDefeat;
    }

    /**
     * @return int
     */
    public function getDefeatOnBlack()
    {
        return $this->blackDefeat;
    }

    /**
     * @param int $whiteDefeat
     */
    public function setDefeatOnWhite($whiteDefeat)
    {
        $this->whiteDefeat = $whiteDefeat;
    }

    /**
     * @return int
     */
    public function getDefeatOnWhite()
    {
        return $this->whiteDefeat;
    }


    /**
     * @param int $white
     */
    public function setWinOnWhite($white)
    {
        $this->whiteWin = $white;
    }

    /**
     * @return int
     */
    public function getWinOnWhite()
    {
        return $this->whiteWin;
    }

    /**
     * @param int $black
     */
    public function setWinOnBlack($black)
    {
        $this->blackWin = $black;
    }

    /**
     * @return int
     */
    public function getWinOnBlack()
    {
        return $this->blackWin;
    }

    /**
     * @param int $white
     */
    public function setWhite($white)
    {
        $this->white = $white;
    }

    /**
     * @return int
     */
    public function getWhite()
    {
        return $this->white;
    }

    /**
     * @param int $black
     */
    public function setBlack($black)
    {
        $this->black = $black;
    }

    /**
     * @return int
     */
    public function getBlack()
    {
        return $this->black;
    }

    /**
     * @param int $defeats
     */
    public function setDefeats($defeats)
    {
        $this->defeats = $defeats;
    }

    /**
     * @return int
     */
    public function getDefeats()
    {
        return $this->defeats;
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
     * @param int $closeMatches
     */
    public function setCloseMatches($closeMatches)
    {
        $this->closeMatches = $closeMatches;
    }

    /**
     * @return int
     */
    public function getCloseMatches()
    {
        return $this->closeMatches;
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

    /**
     * @param int $closeWins
     */
    public function setCloseWins($closeWins)
    {
        $this->closeWins = $closeWins;
    }

    /**
     * @return int
     */
    public function getCloseWins()
    {
        return $this->closeWins;
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
     * @param int $suspended
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
     * @param array $data
     */
    public function populate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }


}