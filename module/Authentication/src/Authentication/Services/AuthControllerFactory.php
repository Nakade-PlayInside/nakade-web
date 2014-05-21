<?php

namespace Authentication\Services;

use Authentication\Controller\AuthController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthControllerFactory
 *
 * @package Authentication\Services
 */
class AuthControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return AuthController
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $services->getServiceLocator();

        if (!$serviceManager->has('Authentication\Services\AuthFormFactory')) {
            throw new \RuntimeException(
                sprintf('Login Form Service is not found.')
            );
        }

        if (!$serviceManager->has('Zend\Authentication\AuthenticationService')) {
            throw new \RuntimeException(
                sprintf('Authentication Service is not found.')
            );
        }

        if (!$serviceManager->has('Authentication\Services\AuthSessionService')) {
            throw new \RuntimeException(
                sprintf('Authentication Session Service is not found.')
            );
        }

        /* @var $form \Nakade\Abstracts\AbstractFormFactory */
        $form           = $serviceManager->get('Authentication\Services\AuthFormFactory');
        $auth           = $serviceManager->get('Zend\Authentication\AuthenticationService');
        $session        = $serviceManager->get('Authentication\Services\AuthSessionService');

        $controller = new AuthController();
        $controller->setFormFactory($form);
        $controller->setService($auth);
        $controller->setSession($session);

        return $controller;
    }
}
