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
    private $closeWithBlack;
    private $closeWithWhite;

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
     * @param int $closeWithBlack
     */
    public function setCloseWithBlack($closeWithBlack)
    {
        $this->closeWithBlack = $closeWithBlack;
    }

    /**
     * @return int
     */
    public function getCloseWithBlack()
    {
        return $this->closeWithBlack;
    }

    /**
     * @param int $closeWithWhite
     */
    public function setCloseWithWhite($closeWithWhite)
    {
        $this->closeWithWhite = $closeWithWhite;
    }

    /**
     * @return int
     */
    public function getCloseWithWhite()
    {
        return $this->closeWithWhite;
    }

    /**
     * @param mixed $whiteLoss
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
     * @return int
     */
    public function getWhiteMatches()
    {
        return $this->whiteMatches;
    }

    /**
     * @param int $whiteWins
     */
    public function setWhiteWins($whiteWins)
    {
        $this->whiteWins = $whiteWins;
    }

    /**
     * @return int
     */
    public function getWhiteWins()
    {
        return $this->whiteWins;
    }


}