<?php

namespace League\Services;


use Nakade\Standings\Results;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Class ResultService
 *
 * @package League\Services
 */
class ResultService implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     *
     * @return Results
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');

        //configuration
        $textDomain = isset($config['League']['text_domain']) ?
            $config['League']['text_domain'] : null;

        $translator = $services->get('translator');

        //result types using i18N
        $results  = new Results();
        $results->setTranslator($translator, $textDomain);
        return $results;
    }
}
