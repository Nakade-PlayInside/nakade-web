<?php

namespace Season\Services;

use Season\Controller\MatchDayController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MatchDayControllerFactory
 *
 * @package Season\Services
 */
class MatchDayControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|MatchDayController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $config  = $serviceManager->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }


        $factory    = $serviceManager->get('Season\Services\SeasonFormService');
        $repository = $serviceManager->get('Season\Services\RepositoryService');
        $service    = $serviceManager->get('Season\Services\MatchDayService');

        $controller = new MatchDayController();
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);
        $controller->setService($service);

        return $controller;
    }
}
