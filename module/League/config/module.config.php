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
            'dateformat' => 'League\View\Helper\DateFormat',
            // more helpers here ...  
        )  
    ),
    
    'controllers' => array(
        'factories' => array(
            'League\Controller\ActualSeason' => 
                    'League\Services\ActualSeasonControllerFactory',
            'League\Controller\Result' => 
                    'League\Services\ResultControllerFactory',
        ),
        'invokables' => array(
            
             'League\Controller\MakeSeason' => 
                     'League\Controller\MakeSeasonController',
             'League\Controller\MakeLeague' => 
                     'League\Controller\MakeLeagueController',
             'League\Controller\MakePlayer' => 
                     'League\Controller\MakePlayerController',
        ),
    ),
    
    'controller_plugins' => array(
      'invokables' => array(
          'season'  => 'League\Controller\Plugin\SeasonPlugin',
          'match'   => 'League\Controller\Plugin\MatchPlugin',
          'league'  => 'League\Controller\Plugin\LeaguePlugin',
          'player'  => 'League\Controller\Plugin\PlayerPlugin',
          'form'    => 'League\Controller\Plugin\FormPlugin',
          
      ),  
    ),
    
    
    'router' => array(
        'routes' => array(
            
            //actual season
            'actual' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/actual',
                    'defaults' => array(
                        'controller' => 'League\Controller\ActualSeason',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //league schedule
                    'schedule' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/schedule[/:lid]',
                            'constraints' => array(
                                'lid'    => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'schedule',
                            ),
                        ),
                    ),
                    
                    //league table
                    'table' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/table[/:lid][/:sort]',
                            'constraints' => array(
                                'lid'    => '[0-9]+',
                                'sort'   => '[a-zA-Z]+',
                            ),
                            'defaults' => array(
                                'action' => 'table',
                            ),
                        ),
                    ),
                    
                )    
            ),
       
            'result' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/result',
                    'defaults' => array(
                        'controller' => 'League\Controller\Result',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //add a result
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/add[/:id]',
                            'constraints' => array(
                                'id'    => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    
                    //open results of a user
                    'myopen' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/myopen',
                            'defaults' => array(
                                'action' => 'myopen',
                            ),
                        ),
                    ),
                )
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
           
            
            
            
            'newleague' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/newleague',
                    'defaults' => array(
                        '__NAMESPACE__' => 'League\Controller',
                        'controller'    => 'League\Controller\MakeLeague',
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
            
            'newplayer' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/newplayer',
                    'defaults' => array(
                        '__NAMESPACE__' => 'League\Controller',
                        'controller'    => 'League\Controller\MakePlayer',
                        'action'        => 'new',
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
            
            'newseason' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/newseason',
                    'defaults' => array(
                        '__NAMESPACE__' => 'League\Controller',
                        'controller'    => 'League\Controller\MakeSeason',
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
            'actual_season'     => 'League\Services\ActualSeasonServiceFactory',
            'result_form'       => 'League\Services\ResultFormFactory',
            'match_result'      => 'League\Services\ResultServiceFactory',
            'translator'    => 'Zend\I18n\Translator\TranslatorServiceFactory',
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
