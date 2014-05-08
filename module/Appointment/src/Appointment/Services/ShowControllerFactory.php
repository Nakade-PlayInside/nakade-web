<?php

namespace Appointment\Services;

use Appointment\Controller\ShowController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AppointmentControllerFactory
 *
 * @package Appointment\Services
 */
class ShowControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ShowController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $services->getServiceLocator();

        /* @var $repository \Appointment\Services\RepositoryService */
        $repository =  $serviceManager->get(
            'Appointment\Services\RepositoryService'
        );

        $controller = new ShowController();
        $controller->setRepository($repository);

        return $controller;

    }
}
