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
        $repository  = $serviceManager->get('User\Services\RepositoryService');
        $mail  = $serviceManager->get('User\Services\MailService');
        $pwdService  = $serviceManager->get('Nakade\Services\PasswordService');

        $controller = new UserController();
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);
        $controller->setMailService($mail);
        $controller->setPasswordService($pwdService);

        return $controller;
    }
}
