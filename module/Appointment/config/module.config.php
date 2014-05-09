<?php
namespace Appointment;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'userInfo'   => 'Appointment\View\Helper\UserInfo',
            // more helpers here ...
        )
    ),

    //controller
    'controllers' => array(
        'invokables' => array(
            'Appointment\Command\Command' => 'Appointment\Command\CommandController',
        ),
        'factories' => array(
            'Appointment\Controller\Appointment' =>
                'Appointment\Services\AppointmentControllerFactory',
            'Appointment\Controller\Confirm' =>
                'Appointment\Services\ConfirmControllerFactory',
            'Appointment\Controller\Show' =>
                'Appointment\Services\ShowControllerFactory'
        ),
    ),

    //command
    'console' => array(
        'router' => array(
            'routes' => array(
                'auto-confirm' => array(
                    'options' => array(
                        // add [ and ] if optional ( ex : [<doname>] )
                        'route' => 'autoConfirm <overdueTime>',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Appointment\Command',
                            'controller' => 'Appointment\Command\Command',
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

        'template_path_stack'   => array(
            __DIR__ . '/../view',
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