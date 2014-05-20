<?php

namespace Authentication\Services;

use Zend\Captcha\Factory as CaptchaFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthCaptchaFactory
 *
 * @package Authentication\Services
 */
class AuthCaptchaFactory implements FactoryInterface
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     *
     * @return \Zend\Captcha\AdapterInterface Captcha
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $spec = array('class'   => 'dumb');
        $config  = $services->get('config');
        if ($config['Application']['contact']['captcha']) {
            $spec    = $config['Application']['contact']['captcha'];
        }

        return CaptchaFactory::factory($spec);
    }
}
