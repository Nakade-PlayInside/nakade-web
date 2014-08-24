<?php

namespace Arbitration\Services;

use Arbitration\Controller\ArbitrationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ArbitrationControllerFactory
 *
 * @package Arbitration\Services
 */
class ArbitrationControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ArbitrationController|mixed
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Arbitration\Services\RepositoryService');
        $controller = new ArbitrationController();

        $controller->setRepository($repository);

        return $controller;
    }


}
