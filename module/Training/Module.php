<?php
/**
 * Module Training
 * 
 * Content site showing adress and dates of the gaming place. Place is shown
 * by Google Maps. Site is supporting I18n. Default language is set on Bootstrap. 
 * 
 * @author  Dr. Holger Maerz <holger@spandaugo.de>
 *  
 */

// module/Training/Module.php
namespace Training;

// Add these import statements:
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;


class Module
{
   
       
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

