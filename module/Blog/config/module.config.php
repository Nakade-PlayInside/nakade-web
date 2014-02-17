<?php
/**
 * Module Blog Config
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */
namespace Blog;
// module/Blog/config/module.config.php:
return array(
    
    //controller
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Blog' => 'Blog\Controller\BlogController',
        ),
    ),

    // routes & defaults
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Blog\Controller\Blog',
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
                'text_domain'   => 'Blog',
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