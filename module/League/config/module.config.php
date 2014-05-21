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
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/actual[/:action][/:sort]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'sort'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\ActualSeason',
                        'action'     => 'index',
                    ),
                ),

            ),

            'result' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/result[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Result',
                        'action'     => 'index',
                    ),
                ),
            ),

            'matchday' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/matchday[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Matchday',
                        'action'     => 'index',
                    ),
                ),
            ),

            'newseason' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/newseason[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Season',
                        'action'     => 'index',
                    ),
                ),
            ),

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

            'player' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/player[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Player',
                        'action'     => 'index',
                    ),
                ),

            ),

            'schedule' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/schedule[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Schedule',
                        'action'     => 'index',
                    ),
                ),

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

            'League\Services\ICalService' =>
                'League\Services\ICalService',

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
