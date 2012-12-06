<?php
/**
 * Module Training Config
 *
 * @author Dr. Holger Maerz <holger@spandaugo.de>
 */
namespace Training;
// module/Training/config/module.config.php:
return array(
    
    //controller
    'controllers' => array(
        'invokables' => array(
            'Training\Controller\Training' => 'Training\Controller\TrainingController',
        ),
    ),

    //Routes and defaults
    'router' => array(
        'routes' => array(
            'training' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/training[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Training\Controller\Training',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    //I18n multilanguage
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
                'text_domain' => __NAMESPACE__,
            ),
        ),
    ),
    
    
   
    //view
    'view_manager' => array(
        'template_path_stack' => array(
            'training' => __DIR__ . '/../view',
        ),
    ),
);