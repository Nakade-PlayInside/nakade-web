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
return array(
    
    'view_helpers' => array(  
        'invokables' => array(  
            'Submenu' => 'Application\View\Helper\Submenu', 
            // more helpers here ...  
        )  
    ),
    
    
    'router' => array(
            'routes' => array(

                'home' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route'    => '/',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action'     => 'index',
                        ),
                    ),
                ),
                
        ),
    ),
    /*
    //navigation
    'navigation' => array(
       
             
       'default' => array(
            'home' => array(
                'label'=>'Home',
                'route'=>'/'
            ),
           
            'location' => array(
                'label' => 'Location',
                'route' => 'training',
            ),
           
           'contact' => array(
                'label' => 'Contact',
                'route' => 'contact',
            ),
           
           'imprint' => array(
                'label' => 'Imprint',
                'route' => 'impressum',
                'controller' => 'Impressum\Controller\Impressum',
                'action' => 'index'
            ),
           
           'admin' => array(
                'label' => 'Admin',
                'uri' => '#acl-admin',
                'resource' => 'nav-admin',
                'pages' => array(
                    
                    'user' => array(
                        'label' => 'User',
                        'route' => 'user',
                        'resource' => 'User\Controller\User',
                    ),
                )
            ),
           
           'profile' => array(
                'label' => 'Profile',
                'route' => 'profile',
                
            ),
           
           'league' => array(
                'label' => 'League',
                'route' => 'actual',
                'controller' => 'League\Controller\ActualSeason',
               
                'pages' => array(
                    
                    'results' => array(
                        'label' => 'Results',
                        'action' => 'opensesults'
                    ),
                    
                    'schedule' => array(
                        'label' => 'Schedule',
                        'action' => 'schedule'
                    ),
                    
                    'table' => array(
                        'label' => 'Standings',
                        'action' => 'table'
                    ),
                    
                    
                )
            ),
           
       ),
       
        
        
    ),*/
    
    'service_manager' => array(
        'factories' => array(
            //'app_navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Application',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 
                     'Application\Controller\IndexController',
           
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . 
                  '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . 
                  '/../view/application/index/index.phtml',
            'error/403'               => __DIR__ . 
                  '/../view/error/403.phtml',
            'error/404'               => __DIR__ . 
                  '/../view/error/404.phtml',
            'error/index'             => __DIR__ . 
                  '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
