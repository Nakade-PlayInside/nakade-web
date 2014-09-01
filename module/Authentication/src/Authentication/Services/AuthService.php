<?php
namespace Authentication\Services;

use Authentication\Adapter\AuthStorage;
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
        $sessionManager = $services->get('Zend\Session\SessionManager');
        $storage = new AuthStorage('nakade', null, $sessionManager);

        //Zend Authentication Services
        return new AuthenticationService($storage, $adapter);

    }

}


