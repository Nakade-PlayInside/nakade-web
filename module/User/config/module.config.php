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
namespace User;

return array(

     'view_helpers' => array(
        'invokables' => array(
            'salutation' => 'User\View\Helper\Salutation',
            'birthday'   => 'User\View\Helper\Birthday',
            'showActive'    => 'User\View\Helper\ShowActive',
            'showTrigger'   => 'User\View\Helper\ShowTrigger',
            'showVerified'  => 'User\View\Helper\ShowVerified',
            'showEdit'      => 'User\View\Helper\ShowEdit',
            'showPwdReset'  => 'User\View\Helper\ShowPwdReset',
            'editBirthday'  => 'User\View\Helper\EditBirthday',
            'editNick'      => 'User\View\Helper\EditNick',
            'editEmail'     => 'User\View\Helper\EditEmail',
            'editPassword'  => 'User\View\Helper\EditPassword',
            'editKgs'  => 'User\View\Helper\EditKgs',
            'editLanguage'  => 'User\View\Helper\EditLanguage',
            // more helpers here ...
        )
    ),

    'controllers' => array(

        'factories' => array(
            'User\Controller\User' =>
                     'User\Services\UserControllerFactory',
            'User\Controller\Profile' =>
                     'User\Services\ProfileControllerFactory',
            'User\Controller\Forgot' =>
                     'User\Services\ForgotControllerFactory',
            'User\Controller\Verify' =>
                     'User\Services\VerifyControllerFactory',

        ),
    ),


    'router' => array(
        'routes' => array(
            //USER
            'user' => array(

                'type'  => 'segment',
                'options' => array(
                    'route'    => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action'     => 'index',
                        'id'     => '0',
                    ),
                ),

            ),
            //PROFILE
            'profile' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/profile[/:action][/:id]',
                    'constraints' => array(
                        'action' =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Profile',
                        'action'     => 'index',
                    ),
                ),
            ),
            //FORGOT PWD
            'forgot' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/forgot[/:action][/:id]',
                    'constraints' => array(
                        'action' =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Forgot',
                        'action'     => 'index',
                    ),
                ),
            ),
            //VERFIFY EMAIL
            'verify' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/verify[/:action][/:id]',
                    'constraints' => array(
                        'action' =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Verify',
                        'action'     => 'index',
                    ),
                ),
            ),

        ),
    ),


    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'MailMessage'   =>
                    'Mail\Service\MailMessageFactory',
            'MailTransport' =>
                    'Mail\Service\MailTransportFactory',
            'User\Factory\UserMailFactory'    =>
                    'User\Factory\UserMailFactory',
            'User\Factory\MapperFactory'  =>
                    'User\Factory\MapperFactory',
            'User\Factory\FormFactory'  =>
                    'User\Factory\FormFactory',
            'User\Services\UserService'    =>
                    'User\Services\UserServiceFactory',
            'User\Services\UserFormService'    =>
                'User\Services\UserFormService',
            'User\Services\RepositoryService'=>
                'User\Services\RepositoryService',
            'translator'  => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'User',
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
