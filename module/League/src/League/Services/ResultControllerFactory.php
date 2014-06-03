<?php

namespace League\Services;

use League\Controller\ResultController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResultControllerFactory
 *
 * @package League\Services
 */
class ResultControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ResultController|mixed
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('League\Services\RepositoryService');
        $factory = $serviceManager->get('League\Services\LeagueFormService');

        $controller = new ResultController();

        $controller->setFormFactory($factory);
        $controller->setRepository($repository);

        return $controller;
    }
}
