<?php

namespace League\Services;

use League\Controller\TableController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TableControllerFactory
 *
 * @package League\Services
 */
class TableControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return TableController|mixed
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();


        $repository = $serviceManager->get('League\Services\RepositoryService');
        $table = $serviceManager->get('League\Services\TableService');

        $controller = new TableController();
        $controller->setRepository($repository);
        $controller->setTableService($table);

        return $controller;
    }
}
