<?php

namespace Moderator\Services;

use Moderator\Controller\ManagerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ManagerControllerFactory
 *
 * @package Moderator\Services
 */
class ManagerControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ManagerController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Moderator\Services\RepositoryService');
        $form = $serviceManager->get('Moderator\Services\FormService');
        $mail = $serviceManager->get('Moderator\Services\MailService');

        $controller = new ManagerController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);


        return $controller;
    }


}
