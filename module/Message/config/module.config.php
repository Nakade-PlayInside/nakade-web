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
namespace Message;

return array(

    'controllers' => array(
        'factories' => array(
            'Message\Controller\Message' =>
                'Message\Services\MessageControllerFactory',
        ),
    ),

    'router' => array(
        'routes' => array(
            'message' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/message[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Message\Controller',
                        'controller' => 'Message\Controller\Message',
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
            'messages' => __DIR__ . '/../view/partial/pagination.phtml', // Note: the key is optional
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Message\Services\RepositoryService'      =>
                'Message\Services\RepositoryService',
            'Message\Services\MailService'      =>
                'Message\Services\MailService',
            'Message\Services\MessageFormService'      =>
                'Message\Services\MessageFormService',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Message',
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
