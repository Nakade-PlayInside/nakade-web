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
            'seasonTitle'  => 'Season\View\Helper\SeasonTitle',
            'state'  => 'Season\View\Helper\SeasonState',
            'matchDate'  => 'Season\View\Helper\MatchDate',
            'accepted'  => 'Season\View\Helper\PlayerAccepted',
            'rulesInfo'  => 'Season\View\Helper\RulesInfo',


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


    'router' => array(
        'routes' => array(

            'season' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/season[/:action][/:id]',
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

            'newLeague' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/newLeague[/:action][/:id]',
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