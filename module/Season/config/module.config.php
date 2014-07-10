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
            'dateCycle'  => 'Season\View\Helper\DateCycle',
            'participationInfo'  => 'Season\View\Helper\ParticipationInfo',


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
            'Season\Controller\MatchDay' =>
                    'Season\Services\MatchDayControllerFactory',
            'Season\Controller\Schedule' =>
                'Season\Services\ScheduleControllerFactory',
            'Season\Controller\Confirm' =>
                'Season\Services\ConfirmControllerFactory',
        ),
    ),


    'router' => array(
        'routes' => array(

            'createSeason' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/createSeason[/:action][/:id]',
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

            'createLeague' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/createLeague[/:action][/:id]',
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

            'invitePlayer' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/invitePlayer[/:action][/:id]',
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

            'configMatchDay' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/configMatchDay[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Season\Controller\MatchDay',
                        'action'     => 'index',
                    ),
                ),

            ),

            'createSchedule' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/createSchedule[/:action][/:id]',
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

            'playerConfirm' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/playerConfirm[/:action][/:id][/:confirm]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'confirm' => '[a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Season\Controller',
                        'controller'    => 'Season\Controller\Confirm',
                        'action'        => 'index',
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
            'Season\Services\ScheduleService'      =>
                'Season\Services\ScheduleService',
            'Season\Services\MailService'      =>
                'Season\Services\MailService',
            'Season\Services\DateHelperService'      =>
                'Season\Services\DateHelperService',
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
