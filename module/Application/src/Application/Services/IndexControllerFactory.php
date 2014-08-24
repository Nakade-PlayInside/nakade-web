<?php

namespace Application\Services;

use Application\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ContactControllerFactory
 *
 * @package Application\Services
 */
class IndexControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $services->getServiceLocator();


        /* @var $service \Permission\Services\VoterService */
        $service =  $serviceManager->get('Permission\Services\VoterService');


        $controller = new IndexController();
        $controller->setService($service);

        return $controller;

    }
}
