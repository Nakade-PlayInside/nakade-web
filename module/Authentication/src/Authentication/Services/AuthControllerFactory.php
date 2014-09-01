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

        if (!$serviceManager->has('Authentication\Services\FormFactory')) {
            throw new \RuntimeException(
                sprintf('Login Form Service is not found.')
            );
        }

        if (!$serviceManager->has('Zend\Authentication\AuthenticationService')) {
            throw new \RuntimeException(
                sprintf('Authentication Service is not found.')
            );
        }

        if (!$serviceManager->has('Authentication\Services\SessionService')) {
            throw new \RuntimeException(
                sprintf('Failure Container not found.')
            );
        }

        /* @var $form \Nakade\Abstracts\AbstractFormFactory */
        $form           = $serviceManager->get('Authentication\Services\FormFactory');
        $auth           = $serviceManager->get('Zend\Authentication\AuthenticationService');
        $container = $serviceManager->get('Authentication\Services\SessionService');

        $controller = new AuthController();
        $controller->setFormFactory($form);
        $controller->setService($auth);
        $controller->setFailureContainer($container);

        return $controller;
    }
}
