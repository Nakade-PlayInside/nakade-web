<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication 
 * for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. 
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;

// Add these import statements:
use Application\Model\Blog;
use Application\Model\BlogTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap($events)
    {
     
        //use browser language for locale (i18n)
        $translator = $events->getApplication()->getServiceManager()->get(
            'translator'
        );
        
        $locale = "de_DE";
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            $locale = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $translator->setLocale(
            \Locale::acceptFromHttp($locale)
        );
                
      
        $eventManager        = $events->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
    }

    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    // Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                               
                'Application\Model\BlogTable' =>  function($serviceManager) {
                    $tableGateway = $serviceManager->get('BlogTableGateway');
                    $table = new BlogTable($tableGateway);
                    return $table;
                },
                'BlogTableGateway' => function ($serviceManager) {
                    $dbAdapter = 
                        $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Blog());
                    return new TableGateway(
                        'wp_posts', $dbAdapter, null, $resultSetPrototype
                    );
                },
            ),
        );
    }
}