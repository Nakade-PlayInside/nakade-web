<?php

namespace Season\Services;

use Season\Controller\ConfirmController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ConfirmControllerFactory
 *
 * @package Season\Services
 */
class ConfirmControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|ConfirmController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $services \Zend\Mvc\Controller\AbstractController */
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Season\Services\RepositoryService');
        $form    = $serviceManager->get('Season\Services\SeasonFormService');

        $controller = new ConfirmController();
        $controller->setRepository($repository);
        $controller->setFormFactory($form);

        return $controller;
    }
}
