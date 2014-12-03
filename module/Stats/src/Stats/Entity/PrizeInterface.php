<?php
namespace Stats\Entity;

use Nakade\TournamentInterface;
/**
 * Interface Prize
 *
 * @package Stats\Entity
 */
Interface PrizeInterface
{
    /**
     * @param TournamentInterface $tournament
     *
     * @return $this
     */
    public function addBronze(TournamentInterface $tournament);

    /**
     * @return array
     */
    public function getBronze();

    /**
     * @return int
     */
    public function getNoBronze();

    /**
     * @param TournamentInterface $tournament
     *
     * @return $this
     */
    public function addGold(TournamentInterface $tournament);

    /**
     * @return array
     */
    public function getGold();

    /**
     * @return int
     */
    public function getNoGold();

    /**
     * @param TournamentInterface $tournament
     *
     * @return $this
     */
    public function addSilver(TournamentInterface $tournament);

    /**
     * @return array
     */
    public function getSilver();

    /**
     * @return int
     */
    public function getNoSilver();

}