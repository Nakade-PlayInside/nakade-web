<?php

namespace Appointment\Services;

use Appointment\Controller\ConfirmController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class AppointmentControllerFactory
 *
 * @package Appointment\Services
 */
class ConfirmControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ConfirmController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $services->getServiceLocator();

        /* @var $repository \Appointment\Services\RepositoryService */
        $repository =  $serviceManager->get(
            'Appointment\Services\RepositoryService'
        );

        /* @var $mailService \Appointment\Services\MailService */
        $mailService = $serviceManager->get(
            'Appointment\Services\MailService'
        );


        $controller = new ConfirmController();
        $controller->setRepository($repository);
        $controller->setMailService($mailService);

        return $controller;

    }
}
