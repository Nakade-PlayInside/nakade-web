<?php
/**
 * Module Nakade
 * 
 * Interfaces, Abstracts and other helper classes multiple used in this project.
 *   
 * 
 * @author  Dr. Holger Maerz <holger@nakade.de>
 *  
 */

// module/Nakade/Module.php
namespace Nakade;



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

