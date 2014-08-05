<?php

namespace Nakade\Abstracts;

use Nakade\FormServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractFormFactory
 *
 * @package Nakade\Abstracts
 */
abstract class AbstractFormFactory extends AbstractTranslation implements FactoryInterface, FormServiceInterface
{
    protected $fieldSetService;
    protected $repository;
    protected $entityManager;
    protected $serviceManager;

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

    /**
     * @param mixed $fieldSetService
     */
    public function setFieldSetService($fieldSetService)
    {
        $this->fieldSetService = $fieldSetService;
    }

    /**
     * @return mixed
     */
    public function getFieldSetService()
    {
        return $this->fieldSetService;
    }

    /**
     * @param mixed $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocatorInterface
     */
    public function setServiceManager(ServiceLocatorInterface $serviceLocatorInterface)
    {
        $this->serviceManager = $serviceLocatorInterface;
    }

}
