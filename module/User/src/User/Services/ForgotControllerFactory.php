<?php

namespace User\Services;

use User\Controller\ForgotController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class ForgotControllerFactory implements FactoryInterface
{

    /**
     * creates the controller and sets a service.
     * Logic eg database action is found in the service.
     * In addition, a form factory is set to receive all the many different
     * forms.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return ForgotController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();

        $factory  = $serviceManager->get('User\Factory\FormFactory');
        $service  = $serviceManager->get('User\Services\UserService');

        $controller  = new ForgotController();

        $controller->setService($service);
        $controller->setFormFactory($factory);


        return $controller;
    }
}
