<?php

namespace Moderator\Services;

use Moderator\Controller\SupportController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SupportControllerFactory
 *
 * @package Moderator\Services
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

        $repository = $serviceManager->get('Moderator\Services\RepositoryService');
        $form = $serviceManager->get('Moderator\Services\FormService');
   //     $mail = $serviceManager->get('League\Services\MailService');
   //     $pagination = $serviceManager->get('League\Services\PaginationService');


        $controller = new SupportController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
     //   $controller->setMailService($mail);
    //    $controller->setPaginationService($pagination);

        return $controller;
    }


}
