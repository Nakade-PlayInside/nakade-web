<?php

namespace Appointment\Services;

use Appointment\Controller\AppointmentController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class AppointmentControllerFactory implements FactoryInterface
{


    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $serviceManager \Zend\ServiceManager\ServiceManager */
        $serviceManager = $services->getServiceLocator();

        $config  = $serviceManager->get('config');
        if ($config instanceof \Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //configuration
        $textDomain = isset($config['Appointment']['text_domain']) ?
            $config['Appointment']['text_domain'] : null;


        $repository =  $serviceManager->get(
            'Appointment\Services\RepositoryService'
        );

        $formFactory =  $serviceManager->get(
            'Appointment\Services\AppointmentFormFactory'
        );



        /* @var $transport \Zend\Mail\Transport\TransportInterface */
     //   $transport = $serviceManager->get('Mail\Services\MailTransportFactory');

        /* @var $message \Mail\Services\MailMessageFactory */
      //  $message =   $serviceManager->get('Mail\Services\MailMessageFactory');

        $translator = $serviceManager->get('translator');


    //    $mailService = new NotifyMail($message, $transport);
   //     $mailService->setTranslator($translator);
   //     $mailService->setTranslatorTextDomain($textDomain);

        $controller = new AppointmentController();
        $controller->setRepository($repository);
        $controller->setFormFactory($formFactory);
     //   $controller->setMailService($mailService);

        return $controller;

    }
}
