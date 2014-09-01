<?php
namespace Authentication\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;

/**
 * Class AuthService
 *
 * @package Authentication\Services
 */
class AuthService implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return mixed|AuthenticationService
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $adapter = $services->get('Authentication\Services\AuthAdapterService');
        $storage = $services->get('Authentication\Services\AuthStorageService');

        //Zend Authentication Services
        return new AuthenticationService($storage, $adapter);

    }

}


