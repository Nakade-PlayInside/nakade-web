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
    private $date;
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
        $this->rounds = $schedule->getNoOfMatchDays();
        $this->date = $schedule->getDate();
        $this->day = $schedule->getDay();
        $this->cycle = $this->getRelativeDateFormat($schedule->getCycle());

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
        $matchDays = range(1, $this->getRounds());
        $scheduleDates = array();

        foreach ($matchDays as $round) {

            $date = clone $this->getDate();
            if ($round>1) {
                $date->modify($this->getCycle());
            }
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
