<?php

namespace Appointment\Services;

use Appointment\Controller\ModeratorController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ModeratorControllerFactory
 *
 * @package Appointment\Services
 */
class ModeratorControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ModeratorController
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


        $controller = new ModeratorController();
        $controller->setRepository($repository);
        $controller->setFormFactory($formFactory);
        $controller->setMailService($mailService);

        return $controller;

    }
}
