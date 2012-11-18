<?php
// module/Training/config/module.config.php:
return array(
    'controllers' => array(
        'invokables' => array(
            'Training\Controller\Training' => 'Training\Controller\TrainingController',
        ),
    ),

    // The following section is new and should be added to your file
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

    'view_manager' => array(
        'template_path_stack' => array(
            'training' => __DIR__ . '/../view',
        ),
    ),
);