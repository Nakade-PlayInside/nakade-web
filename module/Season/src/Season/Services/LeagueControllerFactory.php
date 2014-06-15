<?php

namespace Season\Services;

use Season\Controller\LeagueController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LeagueControllerFactory
 *
 * @package Season\Services
 */
class LeagueControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|LeagueController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $services \Zend\Mvc\Controller\AbstractController */
        $serviceManager = $services->getServiceLocator();

        $factory    = $serviceManager->get('Season\Services\SeasonFormService');
        $repository = $serviceManager->get('Season\Services\RepositoryService');

        $controller = new LeagueController();
        $controller->setRepository($repository);
        $controller->setFormFactory($factory);

        return $controller;
    }
}
