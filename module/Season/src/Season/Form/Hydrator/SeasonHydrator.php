<?php
namespace Season\Form\Hydrator;

use Season\Entity\Season;
use Season\Entity\Time;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class SeasonHydrator
 *
 * @package Season\Form
 */
class SeasonHydrator implements HydratorInterface
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
     * @param object $season
     *
     * @return array
     */
    public function extract($season)
    {
        /* @var $season \Season\Entity\Season */
        return array(
           'tiebreak' => $this->getTieBreakerBySeason($season),

          'winPoints' => $season->getWinPoints(),
          'komi' => $season->getKomi(),
          'number' => $season->getNumber(),
          'associationName' => $season->getAssociation()->getName(),
          'startDate' => $season->getStartDate(),

           //time
          'baseTime' => $this->getBaseTime($season),
        'byoyomi' => 1,
        'additionalTime' => 10,
        'moves' => 30,
        'period' => 1,

          /*
          'byoyomi' => $season->getTime()->getByoyomi()->getId(),
          'additionalTime' => $season->getTime()->getAdditionalTime(),
          'moves' => $season->getTime()->getMoves(),
          'period' => $season->getTime()->getPeriod(),*/

        );
    }

    private function getTieBreakerBySeason(Season $season)
    {
        $tiebreaker1=$tiebreaker2=$tiebreaker3=1;
        if (null!==$season->getTieBreaker1()) {
            $tiebreaker1 = $season->getTieBreaker1()->getId();
        }
        if (null!==$season->getTieBreaker2()) {
            $tiebreaker2 = $season->getTieBreaker2()->getId();
        }
        if (null!==$season->getTieBreaker1()) {
            $tiebreaker3 = $season->getTieBreaker3()->getId();
        }

        return array(
            'tiebreaker1' => $tiebreaker1,
            'tiebreaker2' => $tiebreaker2,
            'tiebreaker3' => $tiebreaker3
        );
    }

    private function getBaseTime(Season $season)
    {
        $baseTime=60;
        if (null!==$season->getTime()) {
            $baseTime = $season->getTime()->getBaseTime();
        }
        return $baseTime;
    }

    /**
     * @param array  $data
     * @param object $season
     *
     * @return object
     */
    public function hydrate(array $data, $season)
    {
        /* @var $season \Season\Entity\Season */
        $season->setNumber($data['number']);
        $season->setKomi($data['komi']);
        $season->setWinPoints($data['winPoints']);

        //tiebreaker
        $tiebreak1 = $this->getTieBreakerById($data['tiebreak']['tiebreaker1']);
        $season->setTieBreaker1($tiebreak1);

        $tiebreak2 = $this->getTieBreakerById($data['tiebreak']['tiebreaker2']);
        $season->setTieBreaker2($tiebreak2);

        $tiebreak3 = $this->getTieBreakerById($data['tiebreak']['tiebreaker3']);
        $season->setTieBreaker3($tiebreak3);

        //time
        $time = $season->getTime();
        $time->exchangeArray($data);
        $byoyomi = $this->getEntityManager()->getReference('Season\Entity\Byoyomi', $data['byoyomi']);
        $time->setByoyomi($byoyomi);
        $season->setTime($time);

        return $season;
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
