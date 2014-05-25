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
namespace Season;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'position'   => 'Season\View\Helper\Position',
            'dateformat' => 'Season\View\Helper\DateFormat',
            'isWinner'   => 'Season\View\Helper\Winner',
            'result'     => 'Season\View\Helper\Result',
            'isActive'   => 'Season\View\Helper\Active',
            'isOpen'     => 'Season\View\Helper\Open',
            'title'  => 'Season\View\Helper\SeasonTitle',
            'state'  => 'Season\View\Helper\SeasonState',

            // more helpers here ...
        )
    ),

    'controllers' => array(
        'factories' => array(
            'Season\Controller\Season' =>
                    'Season\Services\SeasonControllerFactory',
            'Season\Controller\League' =>
                    'Season\Services\LeagueControllerFactory',
            'Season\Controller\Player' =>
                    'Season\Services\PlayerControllerFactory',
            'Season\Controller\Schedule' =>
                    'Season\Services\ScheduleControllerFactory',
        ),

    ),

    'controller_plugins' => array(
      'invokables' => array(
          'season'  => 'Season\Controller\Plugin\SeasonPlugin',
          'match'   => 'Season\Controller\Plugin\MatchPlugin',
          'league'  => 'Season\Controller\Plugin\LeaguePlugin',
          'player'  => 'Season\Controller\Plugin\PlayerPlugin',
          'form'    => 'Season\Controller\Plugin\FormPlugin',

      ),
    ),


    'router' => array(
        'routes' => array(

            'newseason' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/newseason[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Season\Controller\Season',
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
                        'controller' => 'Season\Controller\League',
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
                        'controller' => 'Season\Controller\Player',
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
                        'controller' => 'Season\Controller\Schedule',
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
            'Season\Factory\MapperFactory'      =>
                    'Season\Factory\MapperFactory',
            'Season\Factory\FormFactory'        =>
                    'Season\Factory\FormFactory',
            'Season\Services\SeasonServiceFactory'  =>
                    'Season\Services\SeasonServiceFactory',
            'Season\Services\LeagueServiceFactory'  =>
                    'Season\Services\LeagueServiceFactory',
            'Season\Services\PlayerServiceFactory'  =>
                    'Season\Services\PlayerServiceFactory',
            'Season\Services\ScheduleServiceFactory'  =>
                    'Season\Services\ScheduleServiceFactory',
            'Season\Services\RepositoryService'      =>
                    'Season\Services\RepositoryService',
            'Season\Services\SeasonFormService'      =>
                'Season\Services\SeasonFormService',
            'Season\Services\SeasonFieldsetService'      =>
                'Season\Services\SeasonFieldsetService',
            'translator'    => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Season',
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
