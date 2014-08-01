<?php

namespace Nakade\Services;

use Zend\Captcha\Factory as CaptchaFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class NakadeCaptchaFactory
 *
 * @package Nakade\Services
 */
class NakadeCaptchaFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return \Zend\Captcha\AdapterInterface
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $spec = array('class'   => 'dumb');
        $config  = $services->get('config');
        if ($config['Nakade']['captcha']) {
            $spec    = $config['Nakade']['captcha'];
        }
        return CaptchaFactory::factory($spec);
    }
}
