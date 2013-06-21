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
            'editEmail'      => 'User\View\Helper\EditEmail',
            'editPassword'      => 'User\View\Helper\EditPassword',
            // more helpers here ...  
        )  
    ),
    
    'controllers' => array(
        'factories' => array(
            'User\Controller\User' => 
                     'User\Services\UserControllerFactory',
            'User\Controller\Profile' => 
                     'User\Services\ProfileControllerFactory',
        ),
    ),
    //The name of the route is ‘user’ and has a type of ‘segment’. The segment 
    //route allows us to specify placeholders in the URL pattern (route) that 
    //will be mapped to named parameters in the matched route. 
    //In this case, the route is ``/user[/:action][/:id]`` which will match any 
    //URL that starts with /user. 
    //The next segment will be an optional action name, and then finally the 
    //next segment will be mapped to an optional id. 
    //The square brackets indicate that a segment is optional. 
    //The constraints section allows us to ensure that the characters within a 
    //segment are as expected, so we have limited actions to starting with a 
    //letter and then subsequent characters only being alphanumeric, underscore 
    //or hyphen. We also limit the id to a number.
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'segment',
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
            'User\Factory\MailFactory'    => 
                    'User\Factory\MailFactory',
            'User\Factory\MapperFactory'  => 
                    'User\Factory\MapperFactory',
            'User\Factory\FormFactory'  => 
                    'User\Factory\FormFactory',
            'User\Services\ProfileService' => 
                    'User\Services\ProfileServiceFactory',
            'User\Services\UserService'    => 
                    'User\Services\UserServiceFactory',
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
