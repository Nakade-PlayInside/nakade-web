<?php
namespace Stats\Entity;

/**
 * Interface Prize
 *
 * @package Stats\Entity
 */
Interface PrizeInterface
{

    /**
     * @return $this
     */
    public function addBronze();

    /**
     * @return int
     */
    public function getNoBronze();

    /**
     * @return $this
     */
    public function addGold();

    /**
     * @return int
     */
    public function getNoGold();

    /**
     * @return $this
     */
    public function addSilver();

    /**
     * @return int
     */
    public function getNoSilver();

}