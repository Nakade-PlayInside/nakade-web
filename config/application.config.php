<?php
return array(
    'modules' => array(
        'Application',
        'Impressum',
        'PhlyContact',
        'Blog',
        'User',
        'DoctrineModule',
        'DoctrineORMModule',
        'Nakade',
        'Mail',
        'League',
        'Authentication',
        'Permission',
        'Message',
        'Appointment',

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
