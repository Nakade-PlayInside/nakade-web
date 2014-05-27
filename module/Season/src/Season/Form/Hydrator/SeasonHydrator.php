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

    private $tieBreaker1=1;
    private $tieBreaker2=2;
    private $tieBreaker3=4;
    private $byoyomi=1;

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
        $this->setValues($season);

        /* @var $season \Season\Entity\Season */
        return array(
           'tiebreak' => array(
                'tiebreaker1' => $this->tieBreaker1,
                'tiebreaker2' => $this->tieBreaker2,
                'tiebreaker3' => $this->tieBreaker3
            ),

          'winPoints' => $season->getWinPoints(),
          'komi' => $season->getKomi(),
          'number' => $season->getNumber(),
          'associationName' => $season->getAssociation()->getName(),
          'startDate' => $season->getStartDate(),

           //time
          'baseTime' => $season->getTime()->getBaseTime(),
          'byoyomi' => $this->byoyomi,
          'additionalTime' => $season->getTime()->getAdditionalTime(),
          'moves' => $season->getTime()->getMoves(),
          'period' => $season->getTime()->getPeriod(),


        );
    }

    private function setValues(Season $season)
    {
        if (null!==$season->getTieBreaker1()) {
            $this->tieBreaker1 = $season->getTieBreaker1()->getId();
        }
        if (null!==$season->getTieBreaker2()) {
            $this->tieBreaker2 = $season->getTieBreaker2()->getId();
        }
        if (null!==$season->getTieBreaker3()) {
            $this->tieBreaker3 = $season->getTieBreaker3()->getId();
        }
        if (null!==$season->getTime()->getByoyomi()) {
            $this->byoyomi = $season->getTime()->getByoyomi()->getId();
        }
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
