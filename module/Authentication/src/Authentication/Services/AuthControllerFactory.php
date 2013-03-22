<?php

namespace Authentication\Services;

use Authentication\Controller\AuthController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        
        $serviceLocator = $services->getServiceLocator();
        $form           = $serviceLocator->get('AuthForm');
        

        $controller = new AuthController();
        $controller->setForm($form);
        
        return $controller;
    }
}
