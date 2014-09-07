<?php

namespace Support\Services;

use Support\Controller\FeatureController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FeatureControllerFactory
 *
 * @package Support\Services
 */
class FeatureControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ManagerController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Support\Services\RepositoryService');
        $form = $serviceManager->get('Support\Services\FormService');

        $controller = new FeatureController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);

        return $controller;
    }


}
