<?php
namespace Authentication\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Authentication\Adapter\AuthStorage;
use Zend\Session\SessionManager;

/**
 * Class AuthStorageService
 *
 * @package Authentication\Services
 */
class AuthStorageService implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return AuthStorage
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config = $services->get('Authentication\Services\StorageOptionsService');
        $manager = new SessionManager($config);

        return new AuthStorage('nakade', null, $manager);
    }

}


