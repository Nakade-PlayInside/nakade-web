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
     * creates the authController. Binds the authentication service and
     * the authentication form.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Authentication\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $services \Zend\Mvc\Controller\AbstractController */
        $serviceManager = $services->getServiceLocator();

        $service    = $serviceManager->get('Season\Services\SeasonServiceFactory');

        $factory    = $serviceManager->get('Season\Services\SeasonFormService');

        $repository = $serviceManager->get('Season\Services\RepositoryService');


        $controller = new SeasonController();
        $controller->setService($service);
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);

        return $controller;
    }
}
