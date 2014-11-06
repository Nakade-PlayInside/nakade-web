<?php

namespace Stats\Services;

use Stats\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SeasonControllerFactory
 *
 * @package Season\Services
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
        $controller = new IndexController();
        return $controller;
    }
}
