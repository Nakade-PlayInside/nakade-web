<?php

namespace Season\Services;

use Season\Controller\SeasonController;
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


        $controller = new SeasonController();
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);

        return $controller;
    }
}
