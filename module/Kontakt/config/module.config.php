<?php
// module/Kontakt/config/module.config.php:
return array(
    'controllers' => array(
        'invokables' => array(
            'Kontakt\Controller\Kontakt' => 'Kontakt\Controller\KontaktController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'kontakt' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/kontakt[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Kontakt\Controller\Kontakt',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'kontakt' => __DIR__ . '/../view',
        ),
    ),
);