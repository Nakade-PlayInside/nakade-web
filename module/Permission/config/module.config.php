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
namespace Permission;

return array(

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
