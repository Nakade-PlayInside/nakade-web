<?php
// module/Impressum/config/module.config.php:
return array(
    'controllers' => array(
        'invokables' => array(
            'Impressum\Controller\Impressum' => 'Impressum\Controller\ImpressumController',
        ),
    ),

    // The following section is new and should be added to your file
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
                        'controller' => 'Impressum\Controller\Impressum',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'impressum' => __DIR__ . '/../view',
        ),
    ),
);