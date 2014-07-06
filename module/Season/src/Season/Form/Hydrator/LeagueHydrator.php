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
     * @param array                 $data
     * @param \Season\Entity\League $object
     *
     * @return \Season\Entity\League
     */
    public function hydrate(array $data, $object)
    {
        $playerList = array();
        foreach ($data['addPlayer'] as $participantId) {
            $playerList[] = $this->getParticipantById($participantId);
        }
        $removeList = array();
        foreach ($data['removePlayer'] as $participantId) {
            $removeList[] = $this->getParticipantById($participantId);
        }
        $object->setPlayers($playerList);
        $object->setRemovePlayers($removeList);
        return $object;
    }

    /**
     * @param int $participantId
     *
     * @return \Season\Entity\Participant
     */
    private function getParticipantById($participantId)
    {
        return $this->getEntityManager()->getReference('Season\Entity\Participant', $participantId);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
