<?php
return array(
    'modules' => array(
        'Application',
        'Training',
        'Impressum',
        'PhlyContact',
        'Privacy',
        'Blog',
        'User',
        'DoctrineModule',
        'DoctrineORMModule',
        'Nakade',
        'Mail',
        'League',
        'Authentication',
        'Permission',
        
        ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
    
);
