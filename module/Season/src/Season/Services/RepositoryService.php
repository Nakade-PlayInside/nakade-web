<?php

namespace Season\Services;

use Season\Mapper\LeagueMapper;
use Season\Mapper\ParticipantMapper;
use Season\Mapper\SeasonMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Season\Mapper\MatchMapper;
use \Doctrine\ORM\EntityManager;

/**
 * Class RepositoryService
 *
 * @package Season\Services
 */
class RepositoryService implements FactoryInterface
{

    const MATCH_MAPPER = 'match';
    const SEASON_MAPPER = 'season';
    const PARTICIPANT_MAPPER = 'participant';
    const LEAGUE_MAPPER = 'league';

    private $entityManager;


    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $entityManager \Doctrine\ORM\EntityManager  */
        $entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }
        $this->setEntityManager($entityManager);

        return $this;
    }


    /**
     * @param string $typ
     *
     * @return \Nakade\Abstracts\AbstractMapper
     *
     * @throws \RuntimeException
     */
    public function getMapper($typ)
    {
        switch (strtolower($typ)) {

           case self::MATCH_MAPPER:
               $repository = new MatchMapper();
               break;

           case self::SEASON_MAPPER:
               $repository = new SeasonMapper();
               break;

           case self::PARTICIPANT_MAPPER:
               $repository = new ParticipantMapper();
               break;

           case self::LEAGUE_MAPPER:
               $repository = new LeagueMapper();
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown mapper type was provided.')
               );
        }

        $entityManager = $this->getEntityManager();
        $repository->setEntityManager($entityManager);
        return $repository;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

}
