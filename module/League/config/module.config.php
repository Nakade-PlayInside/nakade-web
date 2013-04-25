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
namespace League;

return array(
    
    'view_helpers' => array(  
        'invokables' => array(  
            'position' => 'League\View\Helper\Position', 
            'player' => 'League\View\Helper\Player',
            // more helpers here ...  
        )  
    ),
    
    'controllers' => array(
        'invokables' => array(
            'League\Controller\League' => 
                     'League\Controller\LeagueController',
            'League\Controller\Schedule' => 
                     'League\Controller\ScheduleController'
        ),
    ),
    
    'router' => array(
        'routes' => array(
            
            'league' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/league[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\League',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'schedule' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/schedule',
                    'defaults' => array(
                        '__NAMESPACE__' => 'League\Controller',
                        'controller'    => 'Schedule',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            
        ),
    ),
    
    
    'view_manager' => array(
        //@todo: view doctype, ect ... s. Application
                
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'League',
            ),
             array( 
                'type'        => 'phparray', 
                'base_dir'    => __DIR__ . '/../resources/languages', 
                'pattern'     => '%s.php',
             
             ), 
        ),
        
    ),
    
    //Doctrine2 
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
        )
    ),
    
);
