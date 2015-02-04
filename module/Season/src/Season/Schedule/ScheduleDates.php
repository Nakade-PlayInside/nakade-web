<?php
namespace Season\Schedule;

use Season\Entity\Schedule;

/**
 * Class ScheduleDates
 *
 * @package Season\Schedule
 */
class ScheduleDates implements WeekdaysInterface
{
    private $cycle;
    private $rounds;
    private $date;
    private $day;
    private $time;

    /**
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        $this->rounds = $schedule->getNoOfMatchDays();
        $this->date = $schedule->getDate();
        $this->day = $schedule->getDay();
        $this->cycle = $this->getRelativeDateFormat($schedule->getCycle());
        $this->time = $schedule->getTime()->format('H:i:s');

        $startTime = $schedule->getTime()->format('H:i:s');
        $startModify = sprintf('%s %s', $this->getWeekday($this->day), $startTime);
        $this->date->modify($startModify);
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

        return $weekday;
    }

    /**
     * @return array
     */
    public function getScheduleDates()
    {
        $matchDays = range(1, $this->getRounds());
        $scheduleDates = array();

        foreach ($matchDays as $round) {

            $date = clone $this->getDate();
            if ($round>1) {
                $date->modify($this->getCycle());
            }
            $date->modify($this->time);//correct the time
            $scheduleDates[$round]=$date;
            $this->setDate($date);
        }

        return $scheduleDates;
    }

    /**
     * @param int $cycle
     *
     * @return string
     */
    public function getRelativeDateFormat($cycle)
    {

        switch ($cycle) {
            case 1:
                $dateCycle="next day";
                break;
            case 5:
                $dateCycle="next weekday";
                break;
            case 7:
                $dateCycle="next week";
                break;
            case 14:
                $dateCycle="next fortnight";
                break;
            case 21:
                $dateCycle="+3 week";
                break;
            case 30:
                $dateCycle=sprintf("+4 %s", strtolower($this->getWeekday($this->day)));
                break;
            default:
                $dateCycle=sprintf("+ %d day", $cycle);
        }

        return $dateCycle;
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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

}
