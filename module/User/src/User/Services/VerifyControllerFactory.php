<?php

namespace User\Services;

use User\Controller\VerifyController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class VerifyControllerFactory implements FactoryInterface
{

    /**
     * creates the controller and sets a service.
     * Logic eg database action is found in the service.
     * In addition, a form factory is set to receive all the many different
     * forms.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return VerifyController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();
        $service  = $serviceManager->get('User\Services\UserService');

        $controller  = new VerifyController();
        $controller->setService($service);

        return $controller;
    }
}
