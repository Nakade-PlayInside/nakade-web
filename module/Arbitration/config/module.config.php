<?php
namespace Support;

return array(

    'controllers' => array(
        'factories' => array(
            'Arbitration\Controller\Arbitration' =>
                    'Arbitration\Services\ArbitrationControllerFactory',
        ),

    ),

    'router' => array(
        'routes' => array(

            'arbitration' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/arbitration[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Arbitration\Controller\Arbitration',
                        'action'     => 'index',
                    ),
                ),
            ),

           //next route

        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
                'moderator' => __DIR__ . '/../view/partial/pagination.phtml', // Note: the key is optional
        ),

        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Arbitration\Services\RepositoryService' =>
                'Arbitration\Services\RepositoryService',
            'Arbitration\Services\MailService' =>
                'Arbitration\Services\MailService',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Arbitration',
            ),
        ),
    ),

    //Doctrine2
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
           ),
           'orm_default' => array(
               'drivers' => array(
                __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
               )
           )
        )
    ),

);
