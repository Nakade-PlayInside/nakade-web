<?php

namespace Application\Services;

use Zend\Captcha\Factory as CaptchaFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class ContactCaptchaFactory
 *
 * @package Application\Services
 */
class ContactCaptchaFactory implements FactoryInterface
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
        if ($config['captcha']) {
            $spec    = $config['captcha'];
        }

        return CaptchaFactory::factory($spec);
    }
}
