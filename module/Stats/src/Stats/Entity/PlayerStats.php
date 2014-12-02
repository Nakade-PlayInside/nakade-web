<?php
namespace Stats\Entity;

/**
 * Class PlayerStats
 *
 * @package Stats\Entity
 */
class PlayerStats extends Achievement
{
    private $tournaments = array();
    private $matches = array();
    private $wins = array();
    private $loss = array();
    private $draws = array();
    private $consecutiveWins = array();
    private $position = array();

    /**
     * @param mixed $consecutiveWins
     */
    public function setConsecutiveWins($consecutiveWins)
    {
        $this->consecutiveWins = $consecutiveWins;
    }

    /**
     * @return array
     */
    public function getConsecutiveWins()
    {
        return $this->consecutiveWins;
    }

    /**
     * @return mixed
     */
    public function getNoConsecutiveWins()
    {
        return count($this->consecutiveWins);
    }

    /**
     * @param array $matches
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @return int
     */
    public function getNoMatches()
    {
        return count($this->matches);
    }



    /**
     * @param array $draws
     */
    public function setDraws(array $draws)
    {
        $this->draws = $draws;
    }

    /**
     * @return array
     */
    public function getDraws()
    {
        return $this->draws;
    }

    /**
     * @return int
     */
    public function getNoDraw()
    {
        return count($this->draws);
    }

    /**
     * @return int
     */
    public function getNoGames()
    {
        return $this->getNoWin() + $this->getNoDraw() + $this->getNoLoss();
    }

    /**
     * @param array $loss
     */
    public function setLoss(array $loss)
    {
        $this->loss = $loss;
    }

    /**
     * @return array
     */
    public function getLoss()
    {
        return $this->loss;
    }

    /**
     * @return int
     */
    public function getNoLoss()
    {
        return count($this->loss);
    }

    /**
     * @param array $tournaments
     */
    public function setTournaments(array $tournaments)
    {
        $this->tournaments = $tournaments;
    }

    /**
     * @return array
     */
    public function getTournaments()
    {
        return $this->tournaments;
    }

    /**
     * @return int
     */
    public function getNoTournaments()
    {
        return count($this->tournaments);
    }

    /**
     * @param array $wins
     */
    public function setWins(array $wins)
    {
        $this->wins = $wins;
    }

    /**
     * @return array
     */
    public function getWins()
    {
        return $this->wins;
    }

    /**
     * @return int
     */
    public function getNoWin()
    {
        return count($this->wins);
    }

    /**
     * @param int $position
     */
    public function addPosition($position)
    {
        $this->position[] = $position;
    }

    /**
     * @return array
     */
    public function getPosition()
    {
        return $this->position;
    }


}