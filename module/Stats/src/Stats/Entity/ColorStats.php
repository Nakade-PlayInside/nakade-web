<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ColorStats
 *
 * @package Stats\Entity
 */
class ColorStats
{
    private $whiteMatches;
    private $blackMatches;
    private $blackWins;
    private $whiteWins;
    private $blackLoss;
    private $whiteLoss;
    private $blackDraws;
    private $whiteDraws;

    /**
     * @param int $blackDraws
     */
    public function setBlackDraws($blackDraws)
    {
        $this->blackDraws = $blackDraws;
    }

    /**
     * @return int
     */
    public function getBlackDraws()
    {
        return $this->blackDraws;
    }

    /**
     * @param int $blackLoss
     */
    public function setBlackLoss($blackLoss)
    {
        $this->blackLoss = $blackLoss;
    }

    /**
     * @return int
     */
    public function getBlackLoss()
    {
        return $this->blackLoss;
    }

    /**
     * @param int $blackMatches
     */
    public function setBlackMatches($blackMatches)
    {
        $this->blackMatches = $blackMatches;
    }

    /**
     * @return int
     */
    public function getBlackMatches()
    {
        return $this->blackMatches;
    }

    /**
     * @param int $blackWins
     */
    public function setBlackWins($blackWins)
    {
        $this->blackWins = $blackWins;
    }

    /**
     * @return int
     */
    public function getBlackWins()
    {
        return $this->blackWins;
    }

    /**
     * @param int $whiteDraws
     */
    public function setWhiteDraws($whiteDraws)
    {
        $this->whiteDraws = $whiteDraws;
    }

    /**
     * @return int
     */
    public function getWhiteDraws()
    {
        return $this->whiteDraws;
    }

    /**
     * @param int $whiteLoss
     */
    public function setWhiteLoss($whiteLoss)
    {
        $this->whiteLoss = $whiteLoss;
    }

    /**
     * @return int
     */
    public function getWhiteLoss()
    {
        return $this->whiteLoss;
    }

    /**
     * @param int $whiteMatches
     */
    public function setWhiteMatches($whiteMatches)
    {
        $this->whiteMatches = $whiteMatches;
    }

    /**
     * @return mixed
     */
    public function getWhiteMatches()
    {
        return $this->whiteMatches;
    }

    /**
     * @param mixed $whiteWins
     */
    public function setWhiteWins($whiteWins)
    {
        $this->whiteWins = $whiteWins;
    }

    /**
     * @return mixed
     */
    public function getWhiteWins()
    {
        return $this->whiteWins;
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