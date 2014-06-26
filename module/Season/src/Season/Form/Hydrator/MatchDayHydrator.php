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
class MatchDayHydrator implements HydratorInterface
{

    private $entityManager;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @param \Season\Entity\Schedule $schedule
     *
     * @return array
     */
    public function extract($schedule)
    {
        return array(
           'seasonInfo' => $schedule->getSeasonInfo(),
           'startDate' => $schedule->getDate()->format('Y-m-d'),
           'noOfMatchDays' => $schedule->getNoOfMatchdays(),
           'cycle' => $schedule->getCycle(),
           'matchDay' => $schedule->getMatchDay(),
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
        /* @var $season \Season\Entity\Season */
        $schedule->setDate($data['startDate']);

        return $schedule;
    }


    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
