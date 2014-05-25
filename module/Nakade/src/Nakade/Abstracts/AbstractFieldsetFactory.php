<?php

namespace Nakade\Abstracts;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractFieldsetFactory
 *
 * @package Nakade\Abstracts
 */
abstract class AbstractFieldsetFactory extends AbstractTranslation implements FactoryInterface
{

    protected $repository;

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
     * @return \Zend\Form\Fieldset
     */
    abstract public function getFieldset($typ);

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

}
