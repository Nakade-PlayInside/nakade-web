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
  private $players = array();
  private $removePlayers = array();

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

    /**
     * @param array $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    /**
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param array $removePlayers
     */
    public function setRemovePlayers($removePlayers)
    {
        $this->removePlayers = $removePlayers;
    }

    /**
     * @return array
     */
    public function getRemovePlayers()
    {
        return $this->removePlayers;
    }

    /**
     * for form data
     *
     * @param array $data
     */
    public function exchangeArray($data)
    {

        if (isset($data['noPlayer'])) {
            $this->noPlayers = intval($data['noPlayer']);
        }
        if (isset($data['players'])) {
            $this->players = $data['players'];
        }
        if (isset($data['removePlayers'])) {
            $this->removePlayers = $data['removePlayers'];
        }
    }

    /**
     * needed for form data
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}