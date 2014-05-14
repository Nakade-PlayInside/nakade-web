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

        //configuration
        $config  = $serviceManager->get('config');
        $deadline = isset($config['Appointment']['deadline_date_shift']) ?
            $config['Appointment']['deadline_date_shift'] : null;


        $controller = new ShowController();
        $controller->setRepository($repository);
        $controller->setDeadline($deadline);

        return $controller;

    }
}
