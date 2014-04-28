<?php
namespace Appointment;

return array(

    //controller
    'controllers' => array(
        'invokables' => array(
            'Appointment\Controller\Appointment' =>
                'Appointment\Controller\AppointmentController',
        ),
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

    //view
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',

        'template_path_stack'   => array(
            __DIR__ . '/../view',
        ),
    ),
);