<?php

namespace Support\Services;

use Support\Controller\SupportController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SupportControllerFactory
 *
 * @package Support\Services
 */
class SupportControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return SupportController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Support\Services\RepositoryService');
        $form = $serviceManager->get('Support\Services\FormService');
        $mail = $serviceManager->get('Support\Services\MailService');

        $controller = new SupportController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);

        return $controller;
    }


}
