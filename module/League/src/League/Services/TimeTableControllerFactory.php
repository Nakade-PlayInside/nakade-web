<?php

namespace League\Services;

use League\Controller\TimeTableController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TimeTableControllerFactory
 *
 * @package League\Services
 */
class TimeTableControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return TimeTableController|mixed
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $serviceManager = $services->getServiceLocator();

        $repository    = $serviceManager->get('League\Services\RepositoryService');
        $factory    = $serviceManager->get('League\Services\LeagueFormService');
        $iCal = $serviceManager->get('League\Services\ICalService');
        $mail = $serviceManager->get('League\Services\MailService');
        $voter = $serviceManager->get('League\Services\MatchVoterService');

        $controller = new TimeTableController();
        $controller->setRepository($repository);
        $controller->setFormFactory($factory);
        $controller->setICalService($iCal);
        $controller->setMailService($mail);
        $controller->setService($voter);

        return $controller;
    }
}
