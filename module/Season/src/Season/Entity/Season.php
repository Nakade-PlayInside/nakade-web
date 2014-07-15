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
    private $matches;
    private $leagues;
    private $firstMatchDate;
    private $lastMatchDate;
    private $players;
    private $matchDays;
    private $availablePlayers = array();
    private $unassignedPlayers = array();
    private $isRegistered = false;

    /**
     * construct
     */
    public function __construct()
    {
        $this->time = new Time();
    }

    /**
     * @return \DateTime
     */
    public function getFirstMatchDate()
    {
        return $this->firstMatchDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastMatchDate()
    {
        return $this->lastMatchDate;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @return int
     */
    public function getNoMatches()
    {
        return count($this->matches);
    }

    /**
     * @return array
     */
    public function getOpenMatches()
    {
        return $this->openMatches;
    }

    /**
     * @return int
     */
    public function getNoOpenMatches()
    {
        return count($this->openMatches);
    }

    /**
     * @return array
     */
    public function getLeagues()
    {
        return $this->leagues;
    }

    /**
     * @return int
     */
    public function getNoLeagues()
    {
        return count($this->leagues);
    }

    /**
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @return array
     */
    public function getNoPlayers()
    {
        return count($this->players);
    }

    /**
     * @return bool
     */
    public function hasSchedule()
    {
        return !empty($this->matches);
    }

    /**
     * @return bool
     */
    public function hasLeagues()
    {
        return count($this->leagues) > 0;
    }

    /**
     * @return bool
     */
    public function hasPlayers()
    {
        return $this->getNoPlayers() > 0;
    }

    /**
     * @param array $availablePlayers
     */
    public function setAvailablePlayers(array $availablePlayers)
    {
        $this->availablePlayers = $availablePlayers;
    }

    /**
     * @return array
     */
    public function getAvailablePlayers()
    {
        return $this->availablePlayers;
    }

    /**
     * @return bool
     */
    public function hasAvailablePlayers()
    {
        return !empty($this->availablePlayers);
    }

    /**
     * @param array $unassignedPlayers
     */
    public function setUnassignedPlayers($unassignedPlayers)
    {
        $this->unassignedPlayers = $unassignedPlayers;
    }

    /**
     * @return bool
     */
    public function hasUnassignedPlayers()
    {
        return !empty($this->unassignedPlayers);
    }

    /**
     * @param boolean $isRegistered
     */
    public function setIsRegistered($isRegistered)
    {
        $this->isRegistered = $isRegistered;
    }

    /**
     * @return boolean
     */
    public function isRegistered()
    {
        return $this->isRegistered;
    }

    /**
     * @param array $matchDays
     */
    public function setMatchDays(array $matchDays)
    {
        $this->matchDays = $matchDays;
    }

    /**
     * @return array
     */
    public function getMatchDays()
    {
        return $this->matchDays;
    }

    /**
     * @return bool
     */
    public function hasMatchDays()
    {
        return !empty($this->matchDays);
    }

    /**
     * @return int
     */
    public function getNoMatchDays()
    {
        return count($this->matchDays);
    }

    /**
     * for form data
     *
     * @param array $data
     */
    public function exchangeArray($data)
    {
        if (isset($data['firstMatchDate'])) {
            $this->firstMatchDate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['firstMatchDate']);
        }
        if (isset($data['lastMatchDate'])) {
            $this->lastMatchDate = \DateTime::createFromFormat('Y-m-d H:i:s', $data['lastMatchDate']);
        }
        if (isset($data['matches'])) {
            $this->matches = $data['matches'];
        }
        if (isset($data['openMatches'])) {
            $this->openMatches = $data['openMatches'];
        }
        if (isset($data['players'])) {
            $this->players = $data['players'];
        }
        if (isset($data['leagues'])) {
            $this->leagues = $data['leagues'];
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
        if (isset($data['matchDays'])) {
            $this->matchDays = $data['matchDays'];
        }
        if (isset($data['availablePlayers'])) {
            $this->availablePlayers = $data['availablePlayers'];
        }
        if (isset($data['unassignedPlayers'])) {
            $this->unassignedPlayers = $data['unassignedPlayers'];
        }
        if (isset($data['isRegistered'])) {
            $this->isRegistered = $data['isRegistered'];
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