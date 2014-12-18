<?php

namespace Stats\Services;

use Stats\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IndexControllerFactory
 *
 * @package Stats\Services
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

        $repository = $serviceManager->get('Stats\Services\RepositoryService');
        $standings  = $serviceManager->get('Stats\Services\PlayerStatsService');
        $table  = $serviceManager->get('Nakade\Services\PlayersTableService');
        $crossTblSrv = $serviceManager->get('Stats\Services\CrossTableService');
        $certificateSrv = $serviceManager->get('Stats\Services\CertificateService');

        $controller = new IndexController();
        $controller->setRepository($repository);
        $controller->setService($standings);
        $controller->setTableService($table);
        $controller->setCrossTableService($crossTblSrv);
        $controller->setCertificateService($certificateSrv);


        return $controller;
    }
}
