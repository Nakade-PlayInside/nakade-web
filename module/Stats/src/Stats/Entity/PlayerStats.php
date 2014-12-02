<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PlayerStats
 *
 * @package Stats\Entity
 */
class PlayerStats
{
    private $noTournaments=0;
    private $noGames=0;
    private $noWin=0;
    private $noLoss=0;
    private $noDraw=0;
    private $noConsecutiveWins=0;

    /**
     * @param mixed $noConsecutiveWins
     */
    public function setNoConsecutiveWins($noConsecutiveWins)
    {
        $this->noConsecutiveWins = $noConsecutiveWins;
    }

    /**
     * @return mixed
     */
    public function getNoConsecutiveWins()
    {
        return $this->noConsecutiveWins;
    }

    /**
     * @param int $noDraw
     */
    public function addNoDraw($noDraw)
    {
        $this->noDraw += $noDraw;
    }

    /**
     * @return int
     */
    public function getNoDraw()
    {
        return $this->noDraw;
    }

    /**
     * @param int $noGames
     */
    public function addNoGames($noGames)
    {
        $this->noGames += $noGames;
    }

    /**
     * @return int
     */
    public function getNoGames()
    {
        return $this->noGames;
    }

    /**
     * @param int $noLoss
     */
    public function addNoLoss($noLoss)
    {
        $this->noLoss += $noLoss;
    }

    /**
     * @return int
     */
    public function getNoLoss()
    {
        return $this->noLoss;
    }

    /**
     * @param mixed $noTournaments
     */
    public function setNoTournaments($noTournaments)
    {
        $this->noTournaments = $noTournaments;
    }

    /**
     * @return int
     */
    public function getNoTournaments()
    {
        return $this->noTournaments;
    }

    /**
     * @param int $noWin
     */
    public function addNoWin($noWin)
    {
        $this->noWin += $noWin;
    }

    /**
     * @return int
     */
    public function getNoWin()
    {
        return $this->noWin;
    }




}