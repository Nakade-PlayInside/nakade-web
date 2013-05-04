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
            'player'   => 'League\View\Helper\Player',
            'dateformat' => 'League\View\Helper\DateFormat',
            // more helpers here ...  
        )  
    ),
    
    'controllers' => array(
        'factories' => array(
            'League\Controller\Season' => 
                    'League\Services\SeasonControllerFactory',
        ),
        'invokables' => array(
            'League\Controller\League' => 
                     'League\Controller\LeagueController',
            'League\Controller\Schedule' => 
                     'League\Controller\ScheduleController',
            'League\Controller\Table' => 
                     'League\Controller\TableController'
        ),
    ),
    
    'controller_plugins' => array(
      'invokables' => array(
          'season'  => 'League\Controller\Plugin\SeasonPlugin',
          'match'   => 'League\Controller\Plugin\MatchPlugin',
          'league'  => 'League\Controller\Plugin\LeaguePlugin',
          'player'  => 'League\Controller\Plugin\PlayerPlugin',
          'table'   => 'League\Controller\Plugin\TablePlugin',
          'form'    => 'League\Controller\Plugin\FormPlugin',
          'result'  => 'League\Controller\Plugin\ResultPlugin',
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
            
            
            'season' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/season',
                    'defaults' => array(
                        '__NAMESPACE__' => 'League\Controller',
                        'controller'    => 'Season',
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
                    'process' => array(
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
           'table' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/table[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Table',
                        'action'     => 'index',
                    ),
                ),
            ), 
        ),
    ),
    
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
                
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
