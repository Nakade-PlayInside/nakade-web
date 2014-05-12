<?php

namespace Message\Services;

use Message\Controller\MessageController;
use Message\Notify\NotifyMail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;


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

        $config  = $serviceManager->get('config');
        if ($config instanceof \Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //configuration
        $textDomain = isset($config['Message']['text_domain']) ?
            $config['Message']['text_domain'] : null;

        $repository =  $serviceManager->get(
            'Message\Services\RepositoryService'
        );
        /* @var $transport \Zend\Mail\Transport\TransportInterface */
        $transport = $serviceManager->get('Mail\Services\MailTransportFactory');


        /* @var $message \Mail\Services\MailMessageFactory */
        $message =   $serviceManager->get('Mail\Services\MailMessageFactory');

        $translator = $serviceManager->get('translator');


        $mailService = new NotifyMail($message, $transport);

        $mailService->setTranslator($translator);
        $mailService->setTranslatorTextDomain($textDomain);

        $controller = new MessageController();

        $controller->setRepository($repository);
        $controller->setTranslator($translator);
        $controller->setMailService($mailService);

        return $controller;
    }
}
