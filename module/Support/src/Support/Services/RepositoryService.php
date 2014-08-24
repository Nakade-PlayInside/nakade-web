<?php

namespace Support\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Support\Mapper;

/**
 * Class RepositoryService
 *
 * @package Support\Services
 */
class RepositoryService implements FactoryInterface
{

    const MANAGER_MAPPER = 'manager';
    const TICKET_MAPPER = 'ticket';

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

            case self::MANAGER_MAPPER:
                $repository = new Mapper\ManagerMapper();
                break;

            case self::TICKET_MAPPER:
                $repository = new Mapper\TicketMapper();
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

