<?php

namespace Support\Services;

use Support\Controller\RefereeController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RefereeControllerFactory
 *
 * @package Support\Services
 */
class RefereeControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return RefereeController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Support\Services\RepositoryService');
        $form = $serviceManager->get('Support\Services\FormService');
        $mail = $serviceManager->get('Support\Services\MailService');

        $controller = new RefereeController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);

        return $controller;
    }


}
