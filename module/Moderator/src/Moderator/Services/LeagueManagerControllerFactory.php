<?php

namespace Moderator\Services;

use Moderator\Controller\LeagueManagerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LeagueManagerControllerFactory
 *
 * @package Moderator\Services
 */
class LeagueManagerControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return LeagueManagerController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository = $serviceManager->get('Moderator\Services\RepositoryService');
        $form = $serviceManager->get('Moderator\Services\FormService');
        $mail = $serviceManager->get('Moderator\Services\MailService');
   //     $pagination = $serviceManager->get('League\Services\PaginationService');


        $controller = new LeagueManagerController();

        $controller->setFormFactory($form);
        $controller->setRepository($repository);
        $controller->setMailService($mail);
    //    $controller->setPaginationService($pagination);

        return $controller;
    }


}
