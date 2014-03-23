<?php

namespace Message\Factory;

use Message\Mapper\MessageMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use RuntimeException;


/**
 * Creates a mapper factory for doctrine database access.
 * Use the fabric method for getting the mapper required.
 */
class MapperFactory implements FactoryInterface
{

    protected $_entityManager;


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
        $this->_entityManager = $services->get('Doctrine\ORM\EntityManager');

        if (null === $this->_entityManager) {
            throw new RuntimeException(
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
     * @throws RuntimeException
     */
    public function getMapper($typ)
    {

        switch (strtolower($typ)) {

           case "message":
               $mapper = new MessageMapper();
               break;


           default:
               throw new RuntimeException(
                   sprintf('An unknown mapper type was provided.')
               );
        }

        $mapper->setEntityManager($this->_entityManager);

        return $mapper;
    }

}
