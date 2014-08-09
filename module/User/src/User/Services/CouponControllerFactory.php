<?php

namespace User\Services;

use User\Controller\CouponController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CouponControllerFactory
 *
 * @package User\Services
 */
class CouponControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|CouponController
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $serviceManager = $services->getServiceLocator();
        $repository  = $serviceManager->get('User\Services\RepositoryService');
        $mail  = $serviceManager->get('User\Services\MailService');
        $form  = $serviceManager->get('User\Services\UserFormService');
      //  $pwdService  = $serviceManager->get('Nakade\Services\PasswordService');

        $controller  = new CouponController();
        $controller->setRepository($repository);
        $controller->setMailService($mail);
        $controller->setFormFactory($form);
      //  $controller->setPasswordService($pwdService);

        return $controller;
    }
}
