<?php
namespace Season\Form\Hydrator;

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
           'tiebreak' => array(
               'tiebreaker1' => $season->getTieBreaker1()->getId(),
               'tiebreaker2' => $season->getTieBreaker2()->getId(),
               'tiebreaker3' => $season->getTieBreaker3()->getId(),
           ),
          'season' => array(
              'winPoints' => $season->getWinPoints(),
              'komi' => $season->getKomi(),
              'number' => $season->getNumber()+1,
              'associationName' => $season->getAssociation()->getName(),
          ),
          'time' => array (
              'baseTime' => $season->getTime()->getBaseTime(),
              'byoyomi' => $season->getTime()->getByoyomi()->getId(),
              'additionalTime' => $season->getTime()->getAdditionalTime(),
              'moves' => $season->getTime()->getMoves(),
              'period' => $season->getTime()->getPeriod(),
           ),
        );
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
        $season->setId(null);
        $season->setNumber($data['season']['number']);
        $season->setKomi($data['season']['komi']);
        $season->setWinPoints($data['season']['winPoints']);

        //tiebreaker
        $tiebreak1 = $this->getTieBreakerById($data['tiebreak']['tiebreaker1']);
        $season->setTieBreaker1($tiebreak1);

        $tiebreak2 = $this->getTieBreakerById($data['tiebreak']['tiebreaker2']);
        $season->setTieBreaker1($tiebreak2);

        $tiebreak3 = $this->getTieBreakerById($data['tiebreak']['tiebreaker3']);
        $season->setTieBreaker1($tiebreak3);

        //time
        $time = new Time();
        $time->exchangeArray($data['time']);
        $byoyomi = $this->getEntityManager()->getReference('Season\Entity\Byoyomi', $data['time']['byoyomi']);
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
