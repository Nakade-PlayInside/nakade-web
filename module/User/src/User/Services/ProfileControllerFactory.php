<?php

namespace User\Services;

use User\Controller\ProfileController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ProfileControllerFactory
 *
 * @package User\Services
 */
class ProfileControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|ProfileController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();

        $factory  = $serviceManager->get('User\Services\UserFormService');
        $repository  = $serviceManager->get('User\Services\RepositoryService');
        $service = $serviceManager->get('Zend\Authentication\AuthenticationService');

        $controller  = new ProfileController();

        $controller->setRepository($repository);
        $controller->setService($service);
        $controller->setFormFactory($factory);


        return $controller;
    }
}
