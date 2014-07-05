<?php
namespace Season\Form\Hydrator;

use Season\Entity\Season;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class SeasonHydrator
 *
 * @package Season\Form
 */
class LeagueHydrator implements HydratorInterface
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
     * @param \Season\Entity\League $object
     *
     * @return array
     */
    public function extract($object)
    {
        return array(
          'seasonNumber' => $object->getSeason()->getNumber(),
          'associationName' => $object->getSeason()->getAssociation()->getName(),
          'leagueNumber' => $object->getNumber(),
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
