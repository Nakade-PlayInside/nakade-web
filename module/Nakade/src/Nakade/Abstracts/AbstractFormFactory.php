<?php

namespace Nakade\Abstracts;

use Nakade\FormServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
abstract class AbstractFormFactory
    extends AbstractTranslation
    implements FactoryInterface, FormServiceInterface
{

    protected $_entityManager;

    /**
    * Sets the EntityManager
    *
    * @param EntityManager $entitymanager
    * @access public
    * @return ActionController
    */
    public function setEntityManager($em)
    {
       $this->_entity_manager = $em;
       return $this;
    }

   /**
    * Returns the EntityManager
    *
    * Fetches the EntityManager from ServiceLocator if it has not been initiated
    * and then returns it
    *
    * @access public
    * @return EntityManager
    */
    public function getEntityManager()
    {
       return $this->_entity_manager;
    }

    /**
    * Returns true if EntityManager is set
    *
    * @access public
    * @return bool
    */
    public function hasEntityManager()
    {
       return isset($this->_entity_manager);
    }

    /**
    * saves the entity.
    * return true on success
    *
    * @param Entity $entity
    */
    public function save($entity)
    {

       if($entity===null) {
           return $this->getEntityManager()->flush();
       }

       $this->getEntityManager()->persist($entity);
       $this->getEntityManager()->flush($entity);

    }

    /**
     * implement ServiceLocator
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \User\Services\FormFactory
     */
    abstract public function createService(ServiceLocatorInterface $services);

    /**
     * @param string $typ
     *
     * @return \Zend\Form\Form
     */
    abstract public function getForm($typ);

}
