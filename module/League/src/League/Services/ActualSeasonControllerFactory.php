<?php

namespace League\Services;

use League\Controller\ActualSeasonController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory.
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class ActualSeasonControllerFactory implements FactoryInterface
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


        $repository    = $serviceManager->get('League\Services\RepositoryService');
        $iCal    = $serviceManager->get('League\Services\ICalService');

        $controller = new ActualSeasonController();
        $controller->setICalService($iCal);
        $controller->setRepository($repository);

        return $controller;
    }
}
