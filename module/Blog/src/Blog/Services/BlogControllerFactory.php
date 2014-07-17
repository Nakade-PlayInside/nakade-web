<?php

namespace Blog\Services;

use Blog\Controller\BlogController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BlogControllerFactory
 *
 * @package Blog\Services
 */
class BlogControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return BlogController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        /* @var $services \Zend\Mvc\Controller\AbstractController */
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Blog\Services\RepositoryService');
        $controller = new BlogController();
        $controller->setRepository($repository);

        return $controller;
    }
}
