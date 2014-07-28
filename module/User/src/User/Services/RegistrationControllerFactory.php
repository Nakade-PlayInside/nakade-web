<?php

namespace User\Services;

use User\Controller\RegistrationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RegistrationControllerFactory
 *
 * @package User\Services
 */
class RegistrationControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|RegistrationController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();
        $repository  = $serviceManager->get('User\Services\RepositoryService');
        $mail  = $serviceManager->get('User\Services\MailService');
        $form  = $serviceManager->get('User\Services\UserFormService');
        $pwdService  = $serviceManager->get('Nakade\Services\PasswordService');

        $controller  = new RegistrationController();
        $controller->setRepository($repository);
        $controller->setMailService($mail);
        $controller->setFormFactory($form);
        $controller->setPasswordService($pwdService);

        return $controller;
    }
}
