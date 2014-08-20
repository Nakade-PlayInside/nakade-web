<?php

namespace Moderator\Services;

use Moderator\Controller\TicketController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TicketControllerFactory
 *
 * @package Moderator\Services
 */
class TicketControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return TicketController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Moderator\Services\RepositoryService');
        $form = $serviceManager->get('Moderator\Services\FormService');
        $mail = $serviceManager->get('Moderator\Services\MailService');

        $controller = new TicketController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);

        return $controller;
    }


}
