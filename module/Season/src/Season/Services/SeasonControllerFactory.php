<?php

namespace Season\Services;

use Season\Controller\SeasonController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory.
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
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
        $serviceManager = $services->getServiceLocator();

        $config  = $serviceManager->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

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
