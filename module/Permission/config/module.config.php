<?php
namespace Permission;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'isAllowed' => 'Permission\View\Helper\Voter',
            'isManager' => 'Permission\View\Helper\IsManager',
            // more helpers here ...
        )
    ),

   'controller_plugins' => array(
    'invokables' => array(
        'Authorize' => 'Permission\Controller\Plugin\Authorize',
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Permission\Controller\Forbidden' =>
                     'Permission\Controller\ForbiddenController',

        ),
    ),

    'router' => array(
        'routes' => array(
            'forbidden' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/forbidden[/:action]',
                    'constraints' => array(
                        'action' =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Permission\Controller\Forbidden',
                        'action'     => 'index',
                    ),
                ),
            ),


        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Permission\Services\AclService' =>
                'Permission\Services\AclService',
            'Permission\Services\VoterService' =>
                'Permission\Services\VoterService',
            'translator'        =>
                'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Permission',
            ),

        ),

    ),

    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);
