<?php

namespace Season\Services;

use Season\Controller\PlayerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PlayerControllerFactory
 *
 * @package Season\Services
 */
class PlayerControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|PlayerController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $services \Zend\Mvc\Controller\AbstractController */
        $serviceManager = $services->getServiceLocator();

        $factory    = $serviceManager->get('Season\Services\SeasonFormService');
        $repository = $serviceManager->get('Season\Services\RepositoryService');
        $mail = $serviceManager->get('Season\Services\MailService');


        $controller = new PlayerController();
        $controller->setFormFactory($factory);
        $controller->setRepository($repository);
        $controller->setMailService($mail);

        return $controller;
    }
}
