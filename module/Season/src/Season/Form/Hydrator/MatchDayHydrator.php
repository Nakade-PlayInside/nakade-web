<?php
namespace Season\Form\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class ScheduleHydrator
 *
 * @package Season\Form\Hydrator
 */
class MatchDayHydrator implements HydratorInterface
{

    /**
     * @param \Season\Entity\MatchDay $matchDay
     *
     * @return array
     */
    public function extract($matchDay)
    {
        return array(
           'seasonInfo' => $matchDay->getSeason()->getSeasonInfo(),
           'round' => $matchDay->getMatchDay(),
           'date' => $matchDay->getDate()->format('Y-m-d'),
           'time' => $matchDay->getDate()->format('H:i:s'),
       );
    }


    /**
     * @param array                   $data
     * @param \Season\Entity\MatchDay $matchDay
     *
     * @return object
     */
    public function hydrate(array $data, $matchDay)
    {
        $datetime = $data['date']. ' ' . $data['time'];
        $temp = new \DateTime($datetime);
        $matchDay->setDate($temp);

        return $matchDay;
    }


}
