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

    /**
     * @return float
     */
    public function getWinPercentage()
    {
        $result = 0;
        if (!empty($this->played)) {
            $result = $this->getWins() * 100 / $this->getPlayed();
        }
        return number_format($result, 1);
    }

    /**
     * @return string
     */
    public function getDefeatPercentage()
    {
        $result = 0;
        if (!empty($this->played)) {
            $result = $this->getDefeats() * 100 / $this->getPlayed();
        }
        return number_format($result, 1);
    }

    /**
     * @return string
     */
    public function getCloseMatchWinPercentage()
    {
        $result = 0;
        if (!empty($this->closeMatches)) {
            $result = $this->getCloseWins() * 100 / $this->getCloseMatches();
        }
        return number_format($result, 1);
    }

    /**
     * @return string
     */
    public function getWinPercentageOnBlack()
    {
        $result = 0;
        if (!empty($this->black)) {
            $result = $this->getWinOnBlack() * 100 / $this->getBlack();
        }
        return number_format($result, 1);
    }

    /**
     * @return string
     */
    public function getWinPercentageOnWhite()
    {
        $result = 0;
        if (!empty($this->white)) {
            $result = $this->getWinOnWhite() * 100 / $this->getWhite();
        }
        return number_format($result, 1);
    }


}