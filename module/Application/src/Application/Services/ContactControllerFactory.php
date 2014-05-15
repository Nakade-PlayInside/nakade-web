<?php

namespace Application\Services;

use Application\Controller\ContactController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ContactControllerFactory
 *
 * @package Application\Services
 */
class ContactControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return ContactController
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $services->getServiceLocator();


        /* @var $formFactory \Application\Services\ContactFormFactory */
        $formFactory =  $serviceManager->get(
            'Application\Services\ContactFormFactory'
        );

        /* @var $mailService \Appointment\Services\MailService */
        $mailService = $serviceManager->get(
            'Application\Services\MailService'
        );


        $controller = new ContactController();
        $controller->setFormFactory($formFactory);
        $controller->setMailService($mailService);

        return $controller;

    }
}
