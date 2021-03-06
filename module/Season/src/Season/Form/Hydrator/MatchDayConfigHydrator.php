<?php
namespace Season\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class ScheduleHydrator
 *
 * @package Season\Form\Hydrator
 */
class MatchDayConfigHydrator implements HydratorInterface
{

    /**
     * @param \Season\Entity\Schedule $schedule
     *
     * @return array
     */
    public function extract($schedule)
    {
        return array(
           'seasonInfo' => $schedule->getSeason()->getSeasonInfo(),
           'startDate' => $schedule->getDate()->format('Y-m-d'),
           'noOfMatchDays' => $schedule->getNoOfMatchdays(),
           'cycle' => $schedule->getCycle(),
           'day' => $schedule->getDay(),
           'time' => $schedule->getTime(),
       );
    }


    /**
     * @param array                   $data
     * @param \Season\Entity\Schedule $schedule
     *
     * @return object
     */
    public function hydrate(array $data, $schedule)
    {
        $datetime = $data['startDate']. ' ' . $data['time'];
        $temp = new \DateTime($datetime);
        $schedule->setDate($temp);
        $schedule->setTime($temp);

        $schedule->setCycle(intval($data['cycle']));
        $schedule->setDay(intval($data['day']));

        return $schedule;
    }

}
