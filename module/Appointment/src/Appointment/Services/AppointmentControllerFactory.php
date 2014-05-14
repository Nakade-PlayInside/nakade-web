<?php

namespace Appointment\Services;

use Appointment\Controller\AppointmentController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class AppointmentControllerFactory
 *
 * @package Appointment\Services
 */
class AppointmentControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return AppointmentController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $services->getServiceLocator();

        /* @var $repository \Appointment\Services\RepositoryService */
        $repository =  $serviceManager->get(
            'Appointment\Services\RepositoryService'
        );

        /* @var $formFactory \Appointment\Services\AppointmentFormFactory */
        $formFactory =  $serviceManager->get(
            'Appointment\Services\AppointmentFormFactory'
        );

        /* @var $mailService \Appointment\Services\MailService */
        $mailService = $serviceManager->get(
            'Appointment\Services\MailService'
        );


        /* @var $validService \Appointment\Services\AppointmentValidService */
        $validService = $serviceManager->get(
            'Appointment\Services\AppointmentValidService'
        );

        $controller = new AppointmentController();
        $controller->setRepository($repository);
        $controller->setFormFactory($formFactory);
        $controller->setMailService($mailService);
        $controller->setService($validService);

        return $controller;

    }
}
