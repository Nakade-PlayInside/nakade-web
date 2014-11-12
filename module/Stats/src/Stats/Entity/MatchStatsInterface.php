<?php
namespace Stats\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interface BasicMatchStats
 *
 * @package Stats\Entity
 */
interface MatchStatsInterface
{
    /**
     * @param int $white
     */
    public function setWinOnWhite($white);

    /**
     * @return int
     */
    public function getWinOnWhite();

    /**
     * @param int $black
     */
    public function setWinOnBlack($black);

    /**
     * @return int
     */
    public function getWinOnBlack();

    /**
     * @param int $white
     */
    public function setWhite($white);

    /**
     * @return int
     */
    public function getWhite();

    /**
     * @param int $black
     */
    public function setBlack($black);

    /**
     * @return int
     */
    public function getBlack();

    /**
     * @param int $defeats
     */
    public function setDefeats($defeats);

    /**
     * @return int
     */
    public function getDefeats();

    /**
     * @param int $draws
     */
    public function setDraws($draws);

    /**
     * @return int
     */
    public function getDraws();

    /**
     * @param int $played
     */
    public function setPlayed($played);

    /**
     * @return int
     */
    public function getPlayed();

    /**
     * @param int $total
     */
    public function setTotal($total);

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @param int $closeMatches
     */
    public function setCloseMatches($closeMatches);

    /**
     * @return int
     */
    public function getCloseMatches();

    /**
     * @param int $wins
     */
    public function setWins($wins);

    /**
     * @return int
     */
    public function getWins();

    /**
     * @param int $closeWins
     */
    public function setCloseWins($closeWins);

    /**
     * @return int
     */
    public function getCloseWins();

    /**
     * @param int $lostByForfeit
     */
    public function setLostByForfeit($lostByForfeit);

    /**
     * @return int
     */
    public function getLostByForfeit();

    /**
     * @param int $suspended
     */
    public function setSuspended($suspended);

    /**
     * @return int
     */
    public function getSuspended();

    /**
     * @param int $lostOnTime
     */
    public function setLostOnTime($lostOnTime);

    /**
     * @return int
     */
    public function getLostOnTime();

    /**
     * @param array $data
     */
    public function populate(array $data);

}