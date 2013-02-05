<?php
/**
 * Module Impressum Config
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */
namespace Impressum;
// module/Impressum/config/module.config.php:
return array(
    
    //controller
    'controllers' => array(
        'invokables' => array(
            'Impressum\Controller\Impressum' => 
                  'Impressum\Controller\ImpressumController',
        ),
    ),

    // routes & defaults
    'router' => array(
        'routes' => array(
            'impressum' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/impressum[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Impressum\Controller',
                        'controller'    => 'Impressum\Controller\Impressum',
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
                'text_domain'   => 'Impressum',
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