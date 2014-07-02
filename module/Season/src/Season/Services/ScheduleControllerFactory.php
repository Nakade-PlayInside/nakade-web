<?php

namespace Season\Services;


use Season\Controller\ScheduleController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ScheduleControllerFactory
 *
 * @package Season\Services
 */
class ScheduleControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|ScheduleController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $factory    = $serviceManager->get('Season\Services\SeasonFormService');
        $repository = $serviceManager->get('Season\Services\RepositoryService');
        $service = $serviceManager->get('Season\Services\ScheduleService');

        $controller = new ScheduleController();
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);
        $controller->setService($service);

        return $controller;
    }
}
