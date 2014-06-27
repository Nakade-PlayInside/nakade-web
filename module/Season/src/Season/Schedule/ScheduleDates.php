<?php
namespace Season\Schedule;

use Season\Entity\Schedule;

/**
 * Class ScheduleDates
 *
 * @package Season\Schedule
 */
class ScheduleDates
{
    private $cycle;
    private $rounds;
    private $startDate;
    private $startTime;
    private $day;

    private $weekDays = array(
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday'
    );

    /**
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        $this->cycle = $schedule->getCycle();
        $this->rounds = $schedule->getNoOfMatchDays();
        $this->startDate = $schedule->getDate();
        $this->startTime = $schedule->getTime()->format('H:i:s');
        $this->day = $schedule->getDay();

        $startModify = sprintf('%s %s', $this->getWeekday($this->day), $this->startTime);
        $this->startDate->modify($startModify);
    }

    /**
     * @param int $day
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getWeekday($day)
    {
        if (!array_key_exists($day, $this->weekDays)) {
            throw new \RuntimeException(
                sprintf('The provided weekday does not exist: %s', $day)
            );
        }

        return $this->weekDays[$day];
    }

    /**
     * @return array
     */
    public function getScheduleDates()
    {
        $scheduleDates = array();

        for ($i=0; $this->getRounds()>$i; $i++) {
            $date = clone $this->getStartDate();
            $dateCycle = sprintf('+%d day', $i * $this->getCycle());
            $date->modify($dateCycle);
            $scheduleDates[]=$date;
        }

        return $scheduleDates;
    }

    private function getDateCycle($cycle, $round)
    {
        //todo: unterscheidung täglich, wöchentlich, monatlich, (wochentags)
    }


    /**
     * @return int
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * @return int
     */
    public function getCycle()
    {
        return $this->cycle;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

}
