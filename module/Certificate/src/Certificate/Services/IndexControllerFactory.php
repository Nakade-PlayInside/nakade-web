<?php

namespace Certificate\Services;

use Certificate\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IndexControllerFactory
 *
 * @package Certificate\Services
 */
class IndexControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|IndexController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $services \Zend\Mvc\Controller\AbstractController */
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Certificate\Services\RepositoryService');

        $controller = new IndexController();
        $controller->setRepository($repository);

        return $controller;
    }
}
