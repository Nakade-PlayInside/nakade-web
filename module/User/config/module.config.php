<?php
namespace User;

//todo: cmdController for cronJobs: removing unanswered registrations, inactivation of edited emails w/no confirm
//todo: removing invalid expired coupons
return array(

     'view_helpers' => array(
        'invokables' => array(
            'showAnonymous'     => 'User\View\Helper\ShowAnonymous',
            'showLanguage'  => 'User\View\Helper\ShowLanguage',
            'showPwdInfo'   => 'User\View\Helper\ShowPwdChangeInfo',
            'showSex'       => 'User\View\Helper\ShowSex',
            'showDate'      => 'User\View\Helper\ShowDate',
            'showDateTime'  => 'User\View\Helper\ShowDateTime',
            'showStage'     => 'User\View\Helper\ShowStage',
            'activateUrl'   => 'User\View\Helper\GetActivateUrl',
            'couponStage'   => 'User\View\Helper\ShowCouponStage',
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
            'User\Controller\Registration' =>
                     'User\Services\RegistrationControllerFactory',
            'User\Controller\Coupon' =>
                'User\Services\CouponControllerFactory',


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
            //registration, verify, pwd reset
            'register' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/register[/:action][/:id]',
                    'constraints' => array(
                        'action' =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Registration',
                        'action'     => 'index',
                    ),
                ),
            ),
            //registration, verify, pwd reset
            'coupon' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/coupon[/:action][/:id]',
                    'constraints' => array(
                        'action' =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Coupon',
                        'action'     => 'index',
                    ),
                ),
            ),

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
            'users' => __DIR__ . '/../view/partial/pagination.phtml', // Note: the key is optional
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'User\Services\UserFormService'    =>
                'User\Services\UserFormService',
            'User\Services\RepositoryService'=>
                'User\Services\RepositoryService',
            'User\Services\UserFieldService'=>
                'User\Services\UserFieldService',
            'User\Services\UserFilterService'=>
                'User\Services\UserFilterService',
            'User\Services\MailService'=>
                'User\Services\MailService',
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
