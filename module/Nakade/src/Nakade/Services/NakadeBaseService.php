<?php

namespace Nakade\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Class NakadeBaseService
 *
 * @package Nakade\Services
 */
class NakadeBaseService implements FactoryInterface
{

    protected $services;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\ServiceManager\FactoryInterface
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $this->setServices($services);
        return $this;
    }

    /**
     * @param ServiceLocatorInterface $services
     */
    public function setServices(ServiceLocatorInterface $services)
    {
        $this->services = $services;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServices()
    {
        return $this->services;
    }

}
