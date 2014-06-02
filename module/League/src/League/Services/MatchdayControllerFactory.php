<?php

namespace League\Services;

use League\Controller\MatchdayController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MatchdayControllerFactory
 *
 * @package League\Services
 */
class MatchdayControllerFactory implements FactoryInterface
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
        $factory    = $serviceManager->get('League\Services\LeagueFormService');


        $controller = new MatchdayController();
        $controller->setRepository($repository);
        $controller->setFormFactory($factory);

        return $controller;
    }
}
