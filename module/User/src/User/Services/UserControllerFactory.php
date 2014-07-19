<?php

namespace User\Services;

use User\Controller\UserController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserControllerFactory
 *
 * @package User\Services
 */
class UserControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|UserController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();

        $factory    = $serviceManager->get('User\Services\UserFormService');
        $service    = $serviceManager->get('User\Services\UserService');
        $repository  = $serviceManager->get('User\Services\RepositoryService');

        $controller = new UserController();
        $controller->setService($service);
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);

        return $controller;
    }
}
