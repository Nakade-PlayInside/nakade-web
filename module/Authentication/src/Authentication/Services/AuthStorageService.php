<?php
namespace Authentication\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Authentication\Adapter\AuthStorage;

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
     * @return AuthStorage|mixed
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //lifeTime
        $lifeTimeInDays = isset($config['NakadeAuth']['cookie_life_time']) ?
            $config['NakadeAuth']['cookie_life_time'] : 14;
        $lifeTime = $this->getLifeTimeInSeconds($lifeTimeInDays);

        $authOptions = $services->get('Authentication\Services\AuthOptionsService');

        $storage = new AuthStorage($authOptions);
        $storage->setCookieLifeTime($lifeTime);

        return $storage;

    }

    /**
     * @param int $lifeTimeInDays
     *
     * @return int
     */
    private function getLifeTimeInSeconds($lifeTimeInDays)
    {
        return intval($lifeTimeInDays)*24*60*60;
    }

}


