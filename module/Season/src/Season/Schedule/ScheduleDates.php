<?php
namespace Season\Schedule;

use Season\Entity\Schedule;

/**
 * Calculates a schedule depending on the provided cycle.
 * Just construct and getScheduleDates as an array of dates
 *
 * @package Season\Schedule
 */
class ScheduleDates implements WeekdaysInterface
{
    private $schedule;
    private $scheduleDates;

    /**
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->scheduleDates = array();
        $this->init();
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    private function getWeekday()
    {
        $day = $this->getSchedule()->getDay();
        switch ($day) {
            case self::MONDAY: $weekday = 'Monday';
                break;
            case self::TUESDAY: $weekday = 'Tuesday';
                break;
            case self::WEDNESDAY: $weekday = 'Wednesday';
                break;
            case self::THURSDAY: $weekday = 'Thursday';
                break;
            case self::FRIDAY: $weekday = 'Friday';
                break;
            case self::SATURDAY: $weekday = 'Saturday';
                break;
            case self::SUNDAY: $weekday = 'Sunday';
                break;
            default: throw new \RuntimeException(
                sprintf('The provided weekday does not exist: %s', $day)
            );
        }

        return strtolower($weekday);
    }

    /**
     * @return void
     */
    private function init()
    {
        $matchDays = range(1, $this->getSchedule()->getNoOfMatchDays());
        $date = clone $this->getSchedule()->getDate();

        foreach ($matchDays as $round) {

            if ($round>1) {
                $date = $this->getNextDate($date);
            }
            $this->addTime($date);
            $this->scheduleDates[$round]=$date;
        }
    }

    /**
     * @param \DateTime $prevDate
     *
     * @return \DateTime
     */
    private function getNextDate(\DateTime $prevDate)
    {
        $date = clone $prevDate;
        $format = $this->getRelativeDateFormat();
        $date->modify($format);

        return $date;
    }

    /**
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    private function addTime(\DateTime $date)
    {
        return $date->modify($this->getSchedule()->getTimeAsString());
    }

    /**
     * @return string
     */
    private function getRelativeDateFormat()
    {

        $cycle = $this->getSchedule()->getCycle();
        switch ($cycle) {
            case 1:
                $dateCycle = "next day";
                break;
            case 5:
                $dateCycle = "next weekday";
                break;
            case 7:
                $dateCycle = "+7 day";
                break;
            case 14:
                $dateCycle = "next fortnight";
                break;
            case 21:
                $dateCycle = "+3 week";
                break;
            case 30:
                $dateCycle = sprintf("+5 %s", $this->getWeekday());
                break;
            default:
                $dateCycle = sprintf("+ %d day", $cycle);
        }

        return $dateCycle;
    }

    /**
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @return array
     */
    public function getScheduleDates()
    {
        return $this->scheduleDates;
    }



}
