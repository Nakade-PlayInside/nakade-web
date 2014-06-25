<?php
namespace Season\Form\Hydrator;

use Nakade\Abstracts\AbstractTranslation;
use Season\Form\WeekDayInterface;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;
use Zend\I18n\Translator\Translator;

/**
 * Class ScheduleHydrator
 *
 * @package Season\Form\Hydrator
 */
class MatchDayHydrator extends AbstractTranslation implements HydratorInterface, WeekDayInterface
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
        $seasonInfo =sprintf('%s. %s League',
            $schedule->getSeason()->getNumber(),
            $schedule->getSeason()->getAssociation()->getName()
        );

        $cycle = $schedule->getCycle();
        $cycleInfo = $this->getCycleInfo($cycle);

        $matchDay = $schedule->getMatchDay();
        $matchDayInfo = $this->getWeekDay($matchDay);

        return array(
           'seasonInfo' => $seasonInfo,
           'startDate' => $schedule->getSeason()->getStartDate()->format('Y-m-d'),
           'noOfMatchDays' => $schedule->getNoOfMatchdays(),
           'cycleInfo' => $cycleInfo,
           'matchDayInfo' => $matchDayInfo,
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
     * @param int $days
     *
     * @return string
     */
    private function getCycleInfo($days)
    {
        $info = $this->translate('every %NUMBER% days');
        $info = str_replace('%NUMBER%', $days, $info);

        switch ($days) {

            case self::DAILY:
                $info = $this->translate('daily');
                break;

            case self::WEEKLY:
                $info = $this->translate('weekly');
                break;

            case self::FORTNIGHTLY:
                $info = $this->translate('fortnightly');
                break;

            case self::MONTHLY:
                $info = $this->translate('monthly');
                break;
        }

        if ($days % 7 == 0 && $days/7 > 2) {
            $info = $this->translate('every %NUMBER% weeks');
            $weeks = $days/7;
            $info = str_replace('%NUMBER%', $weeks, $info);
        }

        return $info;
    }

    /**
     * @param int $day
     *
     * @return string
     */
    private function getWeekDay($day)
    {
        $weekDays = $this->getTranslatedWeekdays();
        return $weekDays[$day];
    }

    /**
     * @return array
     */
    private function getTranslatedWeekdays()
    {
        return array(
            self::MONDAY => $this->translate('Monday'),
            self::TUESDAY => $this->translate('Tuesday'),
            self::WEDNESDAY => $this->translate('Wednesday'),
            self::THURSDAY => $this->translate('Thursday'),
            self::FRIDAY => $this->translate('Friday'),
            self::SATURDAY => $this->translate('Saturday'),
            self::SUNDAY => $this->translate('Sunday')
        );
    }


    /**
     * @param int $tiebreakId
     *
     * @return \Season\Entity\TieBreaker
     */

    private function getTieBreakerById($tiebreakId)
    {
        return $this->getEntityManager()->getReference('Season\Entity\TieBreaker', $tiebreakId);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
