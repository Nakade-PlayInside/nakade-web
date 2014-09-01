<?php
namespace Authentication\Services;

use Authentication\Session\FailureContainer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ClassSessionService
 *
 * @package Authentication\Services
 */
class SessionService implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return FailureContainer
     */
    public function createService(ServiceLocatorInterface $services)
    {

       $config  = $services->get('config');
       $maxAuthAttempts = isset($config['NakadeAuth']['max_auth_attempts']) ?
            $config['NakadeAuth']['max_auth_attempts'] : 0;

       return new FailureContainer($maxAuthAttempts);
    }

}


