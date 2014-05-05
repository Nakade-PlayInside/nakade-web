<?php

namespace Appointment\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \League\Mapper\MatchMapper;
use \Appointment\Mapper\AppointmentMapper;

/**
 * Creates a mapper factory for doctrine database access.
 * Use the fabric method for getting the mapper required.
 */
class RepositoryService implements FactoryInterface
{

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
     * fabric method for getting the mail needed. expecting the mail name as
     * string. Throws an exception if provided typ is unknown.
     * Typ: - 'user'    => users and profile
     *
     * @param string $typ
     *
     * @return \Nakade\Abstracts\AbstractMapper
     *
     * @throws \RuntimeException
     */
    public function getMapper($typ)
    {
        switch (strtolower($typ)) {

           case "match":
               $repository = new MatchMapper();
               $repository->setEntityManager($this->entityManager);
               break;

           case "appointment":
               $repository = new AppointmentMapper($this->entityManager);
               break;

           default:
               throw new \RuntimeException(
                   sprintf('An unknown mapper type was provided.')
               );
        }

        return $repository;
    }

}
