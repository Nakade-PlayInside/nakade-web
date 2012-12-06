<?php
/**
 * Module Impressum
 * 
 * Content site showing imprint and contacte data.
 * laguage file set in bootstrap 
 * 
 * @author  Dr. Holger Maerz <holger@spandaugo.de>
 *  
 */

// module/Impressum/Module.php
namespace Impressum;

// Add these import statements:
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;

class Module
{
    
     
    public function onBootstrap($e)
    {
     
        //use browser language for locale (i18n)
        $translator = $e->getApplication()->getServiceManager()->get('translator');
       //->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        $translator
          ->setLocale('de_DE')   
          ->setFallbackLocale('de_DE');
       
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
      
    }
    
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
}

