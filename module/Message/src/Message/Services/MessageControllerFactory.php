<?php

namespace Message\Services;

use Message\Controller\MessageController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory.
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class MessageControllerFactory implements FactoryInterface
{

    /**
     * creates the authController. Binds the authentication service and
     * the authentication form.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Authentication\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();
        $repository =  $serviceManager->get(
            'Message\Services\RepositoryService'
        );


        $translator = $serviceManager->get('translator');

        $controller = new MessageController();
        $controller->setRepository($repository);
        $controller->setTranslator($translator);

        return $controller;
    }
}
