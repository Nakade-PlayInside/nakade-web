<?php

namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a League
 *
 * @ORM\Entity
 * @ORM\Table(name="league")
 */
class League extends LeagueModel
{

  private $noPlayers=0;

    /**
     * @param int $noPlayers
     */
    public function setNoPlayers($noPlayers)
    {
        $this->noPlayers = $noPlayers;
    }

    /**
     * @return int
     */
    public function getNoPlayers()
    {
        return $this->noPlayers;
    }

}