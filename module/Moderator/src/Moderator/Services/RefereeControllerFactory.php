<?php

namespace Moderator\Services;

use Moderator\Controller\RefereeController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RefereeControllerFactory
 *
 * @package Moderator\Services
 */
class RefereeControllerFactory implements FactoryInterface
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

        $controller = new RefereeController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);

        return $controller;
    }


}
