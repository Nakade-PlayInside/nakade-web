<?php
namespace Season\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Schedule
 *
 * @package Season\Entity
 */
class Schedule
{
    private $season;
    private $noOfMatchDays;
    private $date;
    private $matchDay;
    private $cycle;
    private $time;

    /**
     * @param Season $season
     * @param int    $noOfMatchDays
     */
    public function __construct(Season $season, $noOfMatchDays)
    {
        $this->season = $season;
        $this->noOfMatchDays = $noOfMatchDays;
        $this->cycle = $this->getSeason()->getAssociation()->getSeasonDates()->getCycle();
        $this->matchDay = $this->getSeason()->getAssociation()->getSeasonDates()->getDay();
        $this->time = $this->getSeason()->getAssociation()->getSeasonDates()->getTime();
        $this->date = $this->getSeason()->getStartDate();
    }

    /**
     * @return int
     */
    public function getNoOfMatchDays()
    {
        return $this->noOfMatchDays;
    }


    /**
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $cycle
     */
    public function setCycle($cycle)
    {
        $this->cycle = $cycle;
    }

    /**
     * @return int
     */
    public function getCycle()
    {
        return $this->cycle;
    }

    /**
     * @param int $matchDay
     */
    public function setMatchDay($matchDay)
    {
        $this->matchDay = $matchDay;
    }

    /**
     * @return int
     */
    public function getMatchDay()
    {
        return $this->matchDay;
    }

    /**
     * @param Time $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return Time
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function  getSeasonInfo()
    {
        return sprintf('%s. %s League',
            $this->getSeason()->getNumber(),
            $this->getSeason()->getAssociation()->getName()
        );
    }

}