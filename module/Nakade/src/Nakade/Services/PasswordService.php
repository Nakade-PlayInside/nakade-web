<?php

namespace Nakade\Services;

use Nakade\Generators\PasswordGenerator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PasswordService
 *
 * @package Nakade\Services
 */
class PasswordService implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\Captcha\AdapterInterface
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');

        //text domain
        $length = isset($config['Nakade']['pwdLength']) ?
            $config['Nakade']['pwdLength'] : 8;

        return new PasswordGenerator($length);
    }
}
