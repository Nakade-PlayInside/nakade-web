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
            'position'   => 'League\View\Helper\Position', 
            'dateformat' => 'League\View\Helper\DateFormat',
            'isWinner'   => 'League\View\Helper\Winner',
            'result'     => 'League\View\Helper\Result',
            'isActive'   => 'League\View\Helper\Active',
            'isOpen'     => 'League\View\Helper\Open',
            'seasontitle'  => 'League\View\Helper\SeasonTitle',
            
            // more helpers here ...  
        )  
    ),
    
    'controllers' => array(
        'factories' => array(
            'League\Controller\ActualSeason' => 
                    'League\Services\ActualSeasonControllerFactory',
            'League\Controller\Result' => 
                    'League\Services\ResultControllerFactory',
            'League\Controller\Matchday' => 
                    'League\Services\MatchdayControllerFactory',
            'League\Controller\Season' => 
                    'League\Services\SeasonControllerFactory',
            'League\Controller\League' => 
                    'League\Services\LeagueControllerFactory',
            'League\Controller\Player' => 
                    'League\Services\PlayerControllerFactory',
            'League\Controller\Schedule' => 
                    'League\Services\ScheduleControllerFactory',
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
                    
                    //results of a user
                    'myresult' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/myresult',
                            'defaults' => array(
                                'action' => 'myresult',
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
            
            'matchday' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/matchday',
                    'defaults' => array(
                        'controller' => 'League\Controller\Matchday',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //add a result
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/edit[/:id]',
                            'constraints' => array(
                                'id'    => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                   
                )
            ),
            
            'newseason' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/newseason',
                    'defaults' => array(
                        'controller' => 'League\Controller\Season',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //add a result
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    
                    
                )
            ),
           
            'league' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/league',
                    'defaults' => array(
                        'controller' => 'League\Controller\League',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //add a result
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    
                    
                )
            ),
            
            'player' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/player',
                    'defaults' => array(
                        'controller' => 'League\Controller\Player',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //add a result
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    
                    
                )
            ),
            
            'schedule' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/schedule',
                    'defaults' => array(
                        'controller' => 'League\Controller\Schedule',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //add a result
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/add',
                            'defaults' => array(
                                'action' => 'add',
                            ),
                        ),
                    ),
                    
                    
                )
            ),
           //next route
            
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
            'League\Factory\MapperFactory'      => 
                    'League\Factory\MapperFactory',
            'League\Factory\FormFactory'        => 
                    'League\Factory\FormFactory',
            'League\Services\ActualSeasonServiceFactory'     => 
                    'League\Services\ActualSeasonServiceFactory',
            'League\Services\SeasonServiceFactory'  =>
                    'League\Services\SeasonServiceFactory',
            'League\Services\MatchdayServiceFactory'  =>
                    'League\Services\MatchdayServiceFactory',
            'League\Services\LeagueServiceFactory'  =>
                    'League\Services\LeagueServiceFactory',
            'League\Services\PlayerServiceFactory'  =>
                    'League\Services\PlayerServiceFactory',
            'League\Services\ScheduleServiceFactory'  =>
                    'League\Services\ScheduleServiceFactory',
            
            'result_form'       => 'League\Services\ResultFormFactory',
            
            'League\Services\ResultServiceFactory' =>
                    'League\Services\ResultServiceFactory',
            
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
