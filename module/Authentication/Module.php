<?php

namespace Authentication;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Authentication\Services\AuthServiceFactory;

/**
 * User module for collecting contact data and the application's ACL.
 * The ModuleManager will call automatically the methods.
 *  
 * @copyright Copyright (c) 2013 Dr. Holger Maerz 
 * 
 */
class Module implements AutoloaderProviderInterface
{
    /**
     * Adds a class map to the ClassmapAutoloader and the namespace to the 
     * StandardAutoloader. If no files provided in the ClassmapAutoloader, fall
     * back to the StandardAutoloader.
     * 
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * Autoloading the module configuration file.
     * 
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
   
    public function getServiceConfig()
    {
        return array(
            'factories'=>array(
                'Zend\Authentication\AuthenticationService' => 
                    function($serviceManager) {
           
                       $auth = new AuthServiceFactory($serviceManager);
                       return $auth->createService();
           	    },
                            
                'AuthForm' => 
                    function($serviceManager) {
           
                       return new Services\AuthFormFactory(); 
                       
           	    },
                            
                
            ),
        );
    }

   
  
}
