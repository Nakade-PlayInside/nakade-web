<?php

namespace Authentication\Services;

use Authentication\Controller\AuthController;
use Authentication\Session\FailureContainer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory. 
 * 
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class AuthControllerFactory implements FactoryInterface
{
    
    /**
     * creates the authController. Binds the authentication service and
     * the authentication form.
     *   
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \Authentication\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $services)
    {
      
        $serviceLocator = $services->getServiceLocator();
        
        $config  = $serviceLocator->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        $maxAuthAttempts = isset($config['NakadeAuth']['max_auth_attempts']) ? 
           $maxAuthAttempts['NakadeAuth']['max_auth_attempts][$name'] : null;

        $form           = $serviceLocator->get('AuthForm');
        $auth           = $serviceLocator->get(
                'Zend\Authentication\AuthenticationService');
        
        $container      = new FailureContainer();
        $controller = new AuthController($auth, $form, $container);
        
        $controller->setMaxAuthAttempts($maxAuthAttempts);
        
        return $controller;
    }
}
