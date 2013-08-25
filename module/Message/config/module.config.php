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
    
    'view_helpers' => array(  
        'invokables' => array(  
           
            // more helpers here ...  
        )  
    ),
    
    'controllers' => array(
       
        'factories' => array(
            'Message\Controller\Message' => 
                'Message\Services\MessageControllerFactory',
            
        ),
        
    ),
    
    'controller_plugins' => array(
      'invokables' => array(
          
          
      ),  
    ),
    
    
    'router' => array(
        'routes' => array(
            
            //actual season
            'message' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/message',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Message\Controller',
                        'controller' => 'Message\Controller\Message',
                        'action'     => 'index',
                    ),
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    
                    //league schedule
                    'schedule' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/schedule[/:lid]',
                            'constraints' => array(
                                'lid'    => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'schedule',
                            ),
                        ),
                    ),
                    
                    //league table
                    'table' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'   => '/table[/:lid][/:sort]',
                            'constraints' => array(
                                'lid'    => '[0-9]+',
                                'sort'   => '[a-zA-Z]+',
                            ),
                            'defaults' => array(
                                'action' => 'table',
                            ),
                        ),
                    ),
                    
                )    
            ),
       
           
           //next route
            
        ),
    ),
    
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
                
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    'service_manager' => array(
        'factories' => array(
            'Message\Factory\MapperFactory'      => 
                    'Message\Factory\MapperFactory',
            'Message\Services\MessageServiceFactory'      => 
                    'Message\Services\MessageServiceFactory',
            'translator'    => 'Zend\I18n\Translator\TranslatorServiceFactory',
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
