<?php

namespace Nakade;

use Season\Entity\Season;

/**
 * Interface TournamentInterface
 *
 * @package Nakade\Entity
 */
Interface TournamentInterface
{

   /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param Season $season
     */
    public function setSeason(Season $season);

    /**
     * @return Season
     */
    public function getSeason();

}