<?php

namespace League\Services;

use League\Controller\LeagueController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Creates the controller used for authentication.
 * make sure, you have configured the factory in the module configuration
 * file as a controller factory. 
 * 
 * @author Dr.Holger Maerz <grrompf@gmail.com>
 */
class LeagueControllerFactory implements FactoryInterface
{
    
    /**
     * creates the authController. Binds the authentication service and
     * the authentication form.
     *   
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \Authentication\Controller\AuthController
     */
    public function createService(ServiceLocatorInterface $services)
    {
     
        $serviceManager = $services->getServiceLocator();
        
        $config  = $serviceManager->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        $service    = $serviceManager->get('db_mapper');
       
        $controller = new LeagueController();
        $controller->setSeasonMapperService($service);
        
        //configuration 
        $textDomain = isset($config['League']['text_domain']) ? 
            $config['League']['text_domain'] : null;
        
       
        //optional translator
        $translator = $serviceManager->get('translator');
        if(isset($textDomain) && isset($translator))
              $controller->setTranslator($translator, $textDomain);
        
        return $controller;
    }
}
