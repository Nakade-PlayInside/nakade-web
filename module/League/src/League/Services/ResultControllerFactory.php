<?php

namespace League\Services;

use League\Controller\ResultController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory.
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class ResultControllerFactory implements FactoryInterface
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
        $serviceManager = $services->getServiceLocator();

        $config  = $serviceManager->get('config');


        $repository    = $serviceManager->get('League\Services\RepositoryService');

       // $service    = $serviceManager->get('League\Services\ResultServiceFactory');
        $factory    = $serviceManager->get('League\Services\LeagueFormService');

        $controller = new ResultController();
      //  $controller->setService($service);
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);


        return $controller;
    }
}
