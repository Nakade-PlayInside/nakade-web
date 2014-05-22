<?php

namespace Season\Services;

use Season\Mapper\SeasonMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \League\Mapper\MatchMapper;

/**
 * Class RepositoryService
 *
 * @package Season\Services
 */
class RepositoryService implements FactoryInterface
{

    const MATCH_MAPPER = 'match';
    const SEASON_MAPPER = 'season';

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
        //EntityManager for database access by doctrine
        $this->entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (is_null($this->entityManager)) {
            throw new \RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }

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

           default:
               throw new \RuntimeException(
                   sprintf('An unknown mapper type was provided.')
               );
        }

        $repository->setEntityManager($this->entityManager);
        return $repository;
    }

}
