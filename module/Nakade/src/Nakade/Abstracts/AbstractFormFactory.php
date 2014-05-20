<?php

namespace Nakade\Abstracts;

use Nakade\FormServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
abstract class AbstractFormFactory extends AbstractTranslation implements FactoryInterface, FormServiceInterface
{

    protected $entityManager;

    /**
     * @param mixed $em
     *
     * @return $this
     */
    public function setEntityManager($em)
    {
       $this->entityManager = $em;
       return $this;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
       return $this->entityManager;
    }

    /**
    * Returns true if EntityManager is set
    *
    * @access public
    * @return bool
    */
    public function hasEntityManager()
    {
       return isset($this->entityManager);
    }

    /**
     * @param mixed $entity
     *
     * @return mixed
     */
    public function save($entity)
    {

       if (isNull($entity)) {
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
     * @return mixed
     */
    abstract public function createService(ServiceLocatorInterface $services);

    /**
     * @param string $typ
     *
     * @return \Zend\Form\Form
     */
    abstract public function getForm($typ);

}
