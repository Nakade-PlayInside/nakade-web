<?php

namespace Support\Services;

use Support\Controller\ManagerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ManagerControllerFactory
 *
 * @package Support\Services
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

        $repository = $serviceManager->get('Support\Services\RepositoryService');
        $form = $serviceManager->get('Support\Services\FormService');
        $mail = $serviceManager->get('Support\Services\MailService');

        $controller = new ManagerController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);


        return $controller;
    }


}
