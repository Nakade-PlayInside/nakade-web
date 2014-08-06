<?php
namespace Appointment;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'userInfo'   => 'Appointment\View\Helper\UserInfo',
            'confirmed'   => 'Appointment\View\Helper\Confirmed',
            // more helpers here ...
        )
    ),

    //controller
    'controllers' => array(
        'invokables' => array(
            'Appointment\Command\AutoConfirm' => 'Appointment\Command\AutoConfirmController',
            'Appointment\Command\RemoveAppointment' => 'Appointment\Command\RemoveAppointmentController'
        ),
        'factories' => array(
            'Appointment\Controller\Appointment' =>
                'Appointment\Services\AppointmentControllerFactory',
            'Appointment\Controller\Confirm' =>
                'Appointment\Services\ConfirmControllerFactory',
            'Appointment\Controller\Show' =>
                'Appointment\Services\ShowControllerFactory',
            'Appointment\Controller\Moderator' =>
                'Appointment\Services\ModeratorControllerFactory'
        ),
    ),

    //command
    'console' => array(
        'router' => array(
            'routes' => array(
                'auto-confirm' => array(
                    'options' => array(
                        // add [ and ] if optional ( ex : [<doname>] )
                        'route' => 'autoConfirm',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Appointment\Command',
                            'controller' => 'Appointment\Command\AutoConfirm',
                            'action' => 'do'
                        ),
                    ),
                ),
                'remove' => array(
                    'options' => array(
                        // add [ and ] if optional ( ex : [<doname>] )
                        'route' => 'removeAppointment',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Appointment\Command',
                            'controller' => 'Appointment\Command\RemoveAppointment',
                            'action' => 'do'
                        ),
                    ),
                ),
            )
        )
    ),

    // routes & defaults
    'router' => array(
        'routes' => array(
            'appointment' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/appointment[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Appointment\Controller',
                        'controller'    => 'Appointment\Controller\Appointment',
                        'action'        => 'index',
                    ),
                ),
            ),
            'appointmentConfirm' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/appointmentConfirm[/:action][/:id][/:confirm]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'confirm' => '[a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Appointment\Controller',
                        'controller'    => 'Appointment\Controller\Confirm',
                        'action'        => 'index',
                    ),
                ),
            ),
            'appointmentShow' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/appointmentShow[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Appointment\Controller',
                        'controller'    => 'Appointment\Controller\Show',
                        'action'        => 'index',
                    ),
                ),
            ),
            'appointmentModerator' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/appointmentModerator[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Appointment\Controller',
                        'controller'    => 'Appointment\Controller\Moderator',
                        'action'        => 'index',
                    ),
                ),
            ),

        ),
    ),

    //I18n multilanguage
    'translator' => array(

        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Appointment',
            ),
        ),
    ),

    //services
    'service_manager' => array(
        'factories' => array(
            'Appointment\Services\RepositoryService'      =>
                'Appointment\Services\RepositoryService',
            'translator'    => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Appointment\Services\AppointmentFormFactory' =>
                'Appointment\Services\AppointmentFormFactory',
            'Appointment\Services\MailService' =>
                'Appointment\Services\MailService',
            'Appointment\Services\AppointmentValidService' =>
                'Appointment\Services\AppointmentValidService',
        ),
    ),

    //view
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'appointments' => __DIR__ . '/../view/partial/pagination.phtml', // Note: the key is optional
        ),
        'template_path_stack'   => array(
            __DIR__ . '/../view',
        ),
    ),

    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s>',
            'message_close_string'     => '</div>',
        )
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