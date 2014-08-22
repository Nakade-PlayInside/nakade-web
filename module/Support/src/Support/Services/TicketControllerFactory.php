<?php

namespace Support\Services;

use Support\Controller\TicketController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TicketControllerFactory
 *
 * @package Support\Services
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

        $repository = $serviceManager->get('Support\Services\RepositoryService');
        $form = $serviceManager->get('Support\Services\FormService');
        $mail = $serviceManager->get('Support\Services\MailService');

        $controller = new TicketController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);

        return $controller;
    }


}
