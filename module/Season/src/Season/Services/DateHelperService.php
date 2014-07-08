<?php

namespace Season\Services;

use Season\Schedule\DateHelper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DateHelperService implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return DateHelper
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        //text domain
        $textDomain = isset($config['Season']['text_domain']) ?
            $config['Season']['text_domain'] : null;

        $translator = $services->get('translator');

        return new DateHelper($translator, $textDomain);
    }

}
