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
    private $hasMatchDays;
    private $hasAvailablePlayers;

    /**
     * construct
     */
    public function __construct()
    {
        $this->time = new Time();
    }
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
    public function hasStarted()
    {
        return !is_null($this->getFirstMatchDate()) && $this->getFirstMatchDate() <= new \DateTime();

    }

    /**
     * @return bool
     */
    public function hasEnded()
    {
        return $this->hasMatches() && !$this->hasOpenMatches();
    }

    /**
     * @return bool
     */
    public function hasMatches()
    {
        return $this->noMatches > 0;
    }

    /**
     * @param bool $hasMatchDays
     */
    public function setHasMatchDays($hasMatchDays)
    {
        $this->hasMatchDays = $hasMatchDays;
    }

    /**
     * @return bool
     */
    public function hasMatchDays()
    {
        return $this->hasMatchDays;
    }

    /**
     * @return bool
     */
    public function hasOpenMatches()
    {
        return $this->openMatches > 0;
    }

    /**
     * @return bool
     */
    public function hasSchedule()
    {
        return $this->noMatches > 0;
    }

    /**
     * @return bool
     */
    public function hasLeagues()
    {
        return $this->noLeagues > 0;
    }

    /**
     * @return bool
     */
    public function hasPlayers()
    {
        return $this->noPlayers > 0;
    }

    /**
     * @param bool $hasAvailablePlayers
     */
    public function setHasAvailablePlayers($hasAvailablePlayers)
    {
        $this->hasAvailablePlayers = $hasAvailablePlayers;
    }

    /**
     * @return bool
     */
    public function hasAvailablePlayers()
    {
        return $this->hasAvailablePlayers;
    }


    /**
     * @return string
     */
    public function  getSeasonInfo()
    {
        return sprintf('%s. %s League',
            $this->getNumber(),
            $this->getAssociation()->getName()
        );
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
        if (isset($data['winPoints'])) {
            $this->winPoints = intval($data['winPoints']);
        }
        if (isset($data['komi'])) {
            $this->komi = floatval($data['komi']);
        }
        if (isset($data['startDate'])) {
            $this->startDate = \DateTime::createFromFormat('Y-m-d', $data['startDate']);
        }
        if (isset($data['number'])) {
            $this->number = intval($data['number']);
        }
        if (isset($data['association'])) {
            $this->association = $data['association'];
        }
        if (isset($data['time'])) {
            $this->time = $data['time'];
        }
        if (isset($data['tieBreaker1'])) {
            $this->tieBreaker1 = $data['tieBreaker1'];
        }
        if (isset($data['tieBreaker2'])) {
            $this->tieBreaker2 = $data['tieBreaker2'];
        }
        if (isset($data['tieBreaker3'])) {
            $this->tieBreaker3 = $data['tieBreaker3'];
        }
        if (isset($data['hasMatchDays'])) {
            $this->hasMatchDays = $data['hasMatchDays'];
        }
        if (isset($data['hasAvailablePlayers'])) {
            $this->hasAvailablePlayers = $data['hasAvailablePlayers'];
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