<?php

namespace Season\Services;

use League\Controller\PlayerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory.
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class PlayerControllerFactory implements FactoryInterface
{

    /**
     * creates the authController. Binds the authentication service and
     * the authentication form.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \Authentication\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $config  = $serviceManager->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        $service    = $serviceManager->get(
                'League\Services\PlayerServiceFactory'
        );
        $factory    = $serviceManager->get('League\Factory\FormFactory');

        $controller = new PlayerController();
        $controller->setService($service);
        $controller->setFormFactory($factory);

        return $controller;
    }
}
