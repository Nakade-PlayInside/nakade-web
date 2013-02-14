<?php

namespace User;

use User\Model\User;
use User\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use User\MyAdapterFactory;
use Zend\ModuleManager\Feature\ServiceProviderInterface;


/**
 * User module for collecting contact data and the application's ACL.
 * The ModuleManager will call automatically the methods.
 *  
 * @copyright Copyright (c) 2013 Dr. Holger Maerz 
 * 
 */
class Module implements ServiceProviderInterface
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
   
    /**
     * This method returns an array of factories that are all merged together by
     * the ModuleManager before passing to the ServiceManager. The factory for 
     * User\Model\UserTable uses the ServiceManager to create an 
     * UserTableGateway to pass to the UserTable. We also tell the 
     * ServiceManager that an UserTableGateway is created by getting a 
     * Zend\Db\Adapter\Adapter (also from the ServiceManager) and using it to 
     * create a TableGateway object. The TableGateway is told to use an User 
     * object whenever it creates a new result row. The TableGateway classes use
     * the prototype pattern for creation of result sets and entities. This 
     * means that instead of instantiating when required, the system clones a 
     * previously instantiated object. See PHP Constructor Best Practices and 
     * the Prototype Pattern for more details.
     * 
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'myadapter1'        => new MyAdapterFactory('db_user'),
                
                'User\Model\UserTable' =>  function($servman) {
                    $tableGateway = $servman->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($servman) {
                    $dbAdapter = $servman->get('Zend\Db\Adapter\Adapter');
                    $dbAdapter = $servman->get('myadapter1');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway(
                        'album', $dbAdapter, null, $resultSetPrototype
                    );
                },
            ),
        );
    }
  
}
