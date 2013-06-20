<?php

namespace User\Services;

use User\Mapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class MapperFactory implements FactoryInterface
{
    
    protected $entityManager;
    
    public function createService(ServiceLocatorInterface $services)
    {
        //EntityManager for database access by doctrine
        $this->entityManager = $services->get('Doctrine\ORM\EntityManager');
        
        if (null === $this->entityManager) {
            throw new RuntimeException(
                sprintf('Entity manager could not be found in service.')
            );
        }
        
        return $this;
    }
    
    /**
     * receives the points of the provided game statistics
     * @param string $typ
     * @return string
     * @throws RuntimeException
     */
    public function getMapper($typ)
    {
       
        switch (strtolower($typ)) {
           
           case "user":   $mapper = new Mapper\UserMapper();
                          break;
               
           default      :   throw new RuntimeException(
                sprintf('An unknown mapper type was provided.')
           );              
        }
        
        $mapper->setEntityManager($this->entityManager);
        
        return $mapper;
    }      
    
}
