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
    private $day;
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
        $this->day = $this->getSeason()->getAssociation()->getSeasonDates()->getDay();
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
     * @param int $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getTimeAsString()
    {
        return $this->getTime()->format('H:i:s');
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


}