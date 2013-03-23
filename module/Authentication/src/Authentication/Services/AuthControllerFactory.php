<?php

namespace Authentication\Services;

use Authentication\Controller\AuthController;
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
        $form           = $serviceLocator->get('AuthForm');
        $auth           = $serviceLocator->get(
                'Zend\Authentication\AuthenticationService');

        $controller = new AuthController($auth, $form);
       
        
        return $controller;
    }
}
