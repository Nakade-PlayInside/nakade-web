<?php
/**
 * The config information is passed to the relevant components by the
 * ServiceManager. The controllers section provides a list of all the
 * controllers provided by the module.
 *
 * Within the view_manager section, we add our view directory to the
 * TemplatePathStack configuration.
 *
 * @return array
 */
namespace Authentication;

return array(

    'controllers' => array(
        'invokables' => array(
            'Authentication\Controller\Dashboard' =>
                'Authentication\Controller\DashboardController',
        ),
        'factories' => array(
            'Authentication\Controller\Authentication' =>
                    'Authentication\Services\AuthControllerFactory',
        ),
    ),

    'router' => array(
        'routes' => array(

            'login' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/login[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Authentication\Controller',
                        'controller'    => 'Authentication\Controller\Authentication',
                        'action'        => 'login',
                    ),
                ),
            ),


            'dashboard' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/dashboard[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Authentication\Controller',
                        'controller'    => 'Authentication\Controller\Dashboard',
                        'action'        => 'index',
                    ),
                ),
            ),

        ),
    ),


    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',

        'template_path_stack'   => array(
            __DIR__ . '/../view',
        ),

    ),

    'service_manager' => array(
        'factories' => array(

            //neccessary for using authentication in ViewHelper and Controller
            'Zend\Authentication\AuthenticationService'       =>
                'Authentication\Services\AuthService',
            'Authentication\Services\FormFactory'   =>
                'Authentication\Services\FormFactory',
            'Authentication\Services\SessionService'   =>
                'Authentication\Services\SessionService',
            'Authentication\Services\AuthOptionsService'   =>
                'Authentication\Services\AuthOptionsService',
            'Authentication\Services\AuthAdapterService'   =>
                'Authentication\Services\AuthAdapterService',
            'Authentication\Services\AuthStorageService'   =>
                'Authentication\Services\AuthStorageService',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Auth',
            ),
             array(
                'type'        => 'phparray',
                'base_dir'    => __DIR__ . '/../resources/languages',
                'pattern'     => '%s.php',

             ),
        ),

    ),

    //Doctrine Authentication provided by DoctrineModule\Options\Authentication
    //identity class => performing authentication ; this has to be done
    //identity_property => username or email
    //credential_property => password
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
           ),
           'orm_default' => array(
               'drivers' => array(
                __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
               )
           )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'User\Entity\User',
                'identity_property' => 'username',
                'credential_property' => 'password',
            ),
        ),
    ),

);
