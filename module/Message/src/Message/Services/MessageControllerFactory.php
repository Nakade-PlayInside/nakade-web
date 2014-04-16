<?php

namespace Message\Services;

use Message\Controller\MessageController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory.
 *
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class MessageControllerFactory implements FactoryInterface
{

    /**
     * creates the authController. Binds the authentication service and
     * the authentication form.
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Authentication\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();
        $repository =  $serviceManager->get(
            'Message\Services\RepositoryService'
        );

        /* @var $transport \Zend\Mail\Transport\TransportInterface */
        $transport = $serviceManager->get('Mail\Services\MailTransportFactory');

        /* @var $message \Mail\Services\MailMessageFactory */
        $mailService =   $serviceManager->get('Mail\Services\MailMessageFactory');
        $mailService->setTransport($transport);

        $translator = $serviceManager->get('translator');

        $controller = new MessageController();
        $controller->setRepository($repository);
        $controller->setTranslator($translator);
        $controller->setMailService($mailService);

        return $controller;
    }
}
