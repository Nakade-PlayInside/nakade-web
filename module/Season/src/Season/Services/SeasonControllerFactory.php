<?php

namespace Season\Services;

use Season\Controller\SeasonController;
use Season\Schedule\ScheduleMail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SeasonControllerFactory
 *
 * @package Season\Services
 */
class SeasonControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|SeasonController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $services \Zend\Mvc\Controller\AbstractController */
        $serviceManager = $services->getServiceLocator();

        $factory    = $serviceManager->get('Season\Services\SeasonFormService');
        $repository = $serviceManager->get('Season\Services\RepositoryService');
        $service = $serviceManager->get('Season\Services\SeasonService');

        $controller = new SeasonController();
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);
        $controller->setService($service);

        return $controller;
    }
}
