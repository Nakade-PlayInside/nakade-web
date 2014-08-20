<?php
namespace Moderator;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'isActive'   => 'Moderator\View\Helper\IsActive',
            'activateAction'   => 'Moderator\View\Helper\GetActivateAction',
            'ticketStage'   => 'Moderator\View\Helper\GetTicketStage',
            'ticketStageInfo'   => 'Moderator\View\Helper\GetTicketStageInfo',
            // more helpers here ...
        )
    ),

    'controllers' => array(
        'factories' => array(
            'Moderator\Controller\Manager' =>
                    'Moderator\Services\ManagerControllerFactory',
            'Moderator\Controller\Support' =>
                'Moderator\Services\SupportControllerFactory',
            'Moderator\Controller\LeagueManager' =>
                'Moderator\Services\LeagueManagerControllerFactory',
            'Moderator\Controller\Referee' =>
                'Moderator\Services\RefereeControllerFactory',
        ),

    ),

    'router' => array(
        'routes' => array(

            'manager' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/manager[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Moderator\Controller\Manager',
                        'action'     => 'index',
                    ),
                ),
            ),
            'support' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/support[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Moderator\Controller\Support',
                        'action'     => 'index',
                    ),
                ),
            ),
            'leagueManager' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/leagueManager[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Moderator\Controller\LeagueManager',
                        'action'     => 'index',
                    ),
                ),
            ),
            'referee' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/referee[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Moderator\Controller\Referee',
                        'action'     => 'index',
                    ),
                ),
            ),
           //next route

        ),
    ),


    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s>',
            'message_close_string'     => '</div>',
        )
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
                'moderator' => __DIR__ . '/../view/partial/pagination.phtml', // Note: the key is optional
        ),

        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Moderator\Services\RepositoryService' =>
                'Moderator\Services\RepositoryService',
            'Moderator\Services\FormService' =>
                'Moderator\Services\FormService',
            'Moderator\Services\MailService' =>
                'Moderator\Services\MailService',
            'Moderator\Services\PaginationService' =>
                'Moderator\Services\PaginationService',
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Moderator',
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
