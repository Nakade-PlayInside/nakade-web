<?php

namespace League\Services;

use League\Controller\MatchDayController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MatchDayControllerFactory
 *
 * @package League\Services
 */
class MatchDayControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return MatchDayController|mixed
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository    = $serviceManager->get('League\Services\RepositoryService');
        $factory    = $serviceManager->get('League\Services\LeagueFormService');

        $controller = new MatchDayController();
        $controller->setRepository($repository);
        $controller->setFormFactory($factory);

        return $controller;
    }
}
