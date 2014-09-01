<?php
namespace Authentication\Services;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Config\StandardConfig;

/**
 * Class StorageOptionsService
 *
 * @package Authentication\Services
 */
class StorageOptionsService implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return StandardConfig
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //lifeTime
        $lifeTimeInDays = isset($config['NakadeAuth']['cookie_life_time']) ?
            $config['NakadeAuth']['cookie_life_time'] : 14;
        $lifeTime = $this->getLifeTimeInSeconds($lifeTimeInDays);

        $config = new StandardConfig();
        $config->setOptions(array(
            'remember_me_seconds' => $lifeTime,
            'name'                => 'nakade',
            'cookie_domain' => 'nakade.de',
            'cookie_secure' => true,
            'cookie_httponly' => true,
            'use_cookies' => true,
        ));

        return $config;

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


