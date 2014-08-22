<?php
namespace Support;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'isActive'   => 'Support\View\Helper\IsActive',
            'activateAction'   => 'Support\View\Helper\GetActivateAction',
            'ticketStage'   => 'Support\View\Helper\GetTicketStage',
            'ticketStageInfo'   => 'Support\View\Helper\GetTicketStageInfo',
            // more helpers here ...
        )
    ),

    'controllers' => array(
        'factories' => array(
            'Support\Controller\Manager' =>
                    'Support\Services\ManagerControllerFactory',
            'Support\Controller\Support' =>
                'Support\Services\SupportControllerFactory',
            'Support\Controller\Ticket' =>
                'Support\Services\TicketControllerFactory',
            'Support\Controller\Referee' =>
                'Support\Services\RefereeControllerFactory',
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
                        'controller' => 'Support\Controller\Manager',
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
                        'controller' => 'Support\Controller\Support',
                        'action'     => 'index',
                    ),
                ),
            ),
            'ticket' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/ticket[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Support\Controller\Ticket',
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
                        'controller' => 'Support\Controller\Referee',
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
            'Support\Services\RepositoryService' =>
                'Support\Services\RepositoryService',
            'Support\Services\FormService' =>
                'Support\Services\FormService',
            'Support\Services\MailService' =>
                'Support\Services\MailService',
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Support',
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
