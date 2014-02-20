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

