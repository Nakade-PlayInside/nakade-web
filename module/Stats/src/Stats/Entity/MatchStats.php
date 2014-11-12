<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MatchStats
 *
 * @package Stats\Entity
 */
class MatchStats extends AbstractMatchStats
{
    private $closeDefeats;
    private $lostByResign;
    private $highWins;
    private $blackDefeat;
    private $whiteDefeat;
    private $blackDraw;
    private $whiteDraw;

    /**
     * @param int $highWins
     */
    public function setHighWins($highWins)
    {
        $this->highWins = $highWins;
    }

    /**
     * @return int
     */
    public function getHighWins()
    {
        return $this->highWins;
    }


    /**
     * @param int $lostByResign
     */
    public function setLostByResign($lostByResign)
    {
        $this->lostByResign = $lostByResign;
    }

    /**
     * @return int
     */
    public function getLostByResign()
    {
        return $this->lostByResign;
    }

    /**
     * @param int $closeDefeats
     */
    public function setCloseDefeats($closeDefeats)
    {
        $this->closeDefeats = $closeDefeats;
    }

    /**
     * @return int
     */
    public function getCloseDefeats()
    {
        return $this->closeDefeats;
    }


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


}