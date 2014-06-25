<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* Entity Class representing a MatchDay
*
* @ORM\Entity
* @ORM\Table(name="matchDay")
*/
class Schedule
{

    private $season;
    private $noOfMatchDays;

    /**
     * @param int $noOfMatchDays
     */
    public function setNoOfMatchDays($noOfMatchDays)
    {
        $this->noOfMatchDays = $noOfMatchDays;
    }

    /**
     * @return int
     */
    public function getNoOfMatchDays()
    {
        return $this->noOfMatchDays;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    /**
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

}