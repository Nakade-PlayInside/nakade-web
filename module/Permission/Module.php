<?php
namespace Permission;

use Zend\Mvc\MvcEvent,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface
{

    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager        = $event->getApplication()->getEventManager();
        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
        //you can attach other function need here...

    }


    /**
     * Performing auhtorization in all controllers
     *
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function loadConfiguration(MvcEvent $event)
    {
        $application   = $event->getApplication();
        $sm            = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $router = $sm->get('router');
        $request = $sm->get('request');

        $matchedRoute = $router->match($request);
        if (null !== $matchedRoute) {
               $sharedManager->attach(
                   'Zend\Mvc\Controller\AbstractActionController', 'dispatch',
                   function($event) use ($sm) {
                         $sm->get('ControllerPluginManager')
                            ->get('Authorize')
                            ->doAuthorization($event); //pass to the plugin...
                   },
                   2
               );
        }
    }

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

}
