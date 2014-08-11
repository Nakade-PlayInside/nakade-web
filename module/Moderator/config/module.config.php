<?php
namespace Moderator;

return array(

    'view_helpers' => array(
        'invokables' => array(
            // more helpers here ...
        )
    ),

    'controllers' => array(
        'factories' => array(
            'Moderator\Controller\Manager' =>
                    'Moderator\Services\ManagerControllerFactory',
        ),

    ),

    'router' => array(
        'routes' => array(

            'manager' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/manager[/:action][/:sort]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'sort'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Moderator\Controller\Manager',
                        'action'     => 'index',
                    ),
                ),
            ),
           //next route

        ),
    ),


    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s>',
            'message_close_string'     => '</div>',
        )
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
            'Moderator\Services\RepositoryService' =>
                'Moderator\Services\RepositoryService',
            'Moderator\Services\FormService' =>
                'Moderator\Services\FormService',
            'Moderator\Services\MailService' =>
                'Moderator\Services\MailService',
            'Moderator\Services\PaginationService' =>
                'Moderator\Services\PaginationService',
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Moderator',
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
