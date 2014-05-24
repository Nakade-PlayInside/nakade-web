<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeasonModel
 *
 * @package Season\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="season")
 */
class Season extends SeasonModel
{

    private $openMatches;
    private $noMatches;
    private $firstMatchDate;
    private $lastMatchDate;
    private $noLeagues;
    private $noPlayers;

    /**
     * @param \DateTime $firstMatchDate
     */
    public function setFirstMatchDate($firstMatchDate)
    {
        $this->firstMatchDate = $firstMatchDate;
    }

    /**
     * @return \DateTime
     */
    public function getFirstMatchDate()
    {
        return $this->firstMatchDate;
    }

    /**
     * @param \DateTime $lastMatchDate
     */
    public function setLastMatchDate($lastMatchDate)
    {
        $this->lastMatchDate = $lastMatchDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastMatchDate()
    {
        return $this->lastMatchDate;
    }

    /**
     * @param int $noMatches
     */
    public function setNoMatches($noMatches)
    {
        $this->noMatches = $noMatches;
    }

    /**
     * @return int
     */
    public function getNoMatches()
    {
        return $this->noMatches;
    }

    /**
     * @param int $openMatches
     */
    public function setOpenMatches($openMatches)
    {
        $this->openMatches = $openMatches;
    }

    /**
     * @return int
     */
    public function getOpenMatches()
    {
        return $this->openMatches;
    }

    /**
     * @param int $noLeagues
     */
    public function setNoLeagues($noLeagues)
    {
        $this->noLeagues = $noLeagues;
    }

    /**
     * @return int
     */
    public function getNoLeagues()
    {
        return $this->noLeagues;
    }

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
     * @return bool
     */
    public function isReady()
    {
        //@todo: have all registered leagues also matches
        return false;
    }

    /**
     * @return bool
     */
    public function hasStarted()
    {
        return $this->getFirstMatchDate() <= new \DateTime();

    }

    /**
     * @return bool
     */
    public function hasEnded()
    {
        return empty($this->openMatches);
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
        if (isset($data['firstMatchDate'])) {
            $this->firstMatchDate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['firstMatchDate']);
        }
        if (isset($data['lastMatchDate'])) {
            $this->lastMatchDate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['lastMatchDate']);
        }
        if (isset($data['noMatches'])) {
            $this->noMatches = intval($data['noMatches']);
        }
        if (isset($data['openMatches'])) {
            $this->openMatches = intval($data['openMatches']);
        }
        if (isset($data['noPlayers'])) {
            $this->noPlayers = intval($data['noPlayers']);
        }
        if (isset($data['noLeagues'])) {
            $this->noLeagues = intval($data['noLeagues']);
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