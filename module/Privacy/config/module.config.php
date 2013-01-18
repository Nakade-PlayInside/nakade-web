<?php
/**
 * Module Privacy Config
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */
namespace Privacy;
// module/Privacy/config/module.config.php:
return array(
    
    //controller
    'controllers' => array(
        'invokables' => array(
            'Privacy\Controller\Privacy' => 'Privacy\Controller\PrivacyController',
        ),
    ),

    // routes & defaults
    'router' => array(
        'routes' => array(
            'disclaimer' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/privacy[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Privacy\Controller',
                        'controller'    => 'Privacy\Controller\Privacy',
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
                'text_domain'   => 'Privacy',
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