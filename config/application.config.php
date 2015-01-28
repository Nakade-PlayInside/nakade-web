<?php
return array(
    'modules' => array(
        'Application',
        'Blog',
        'User',
        'DoctrineModule',
        'DoctrineORMModule',
        'Nakade',
        'Mail',
        'League',
        'Season',
        'Authentication',
        'Permission',
        'Message',
        'Appointment',
        'Support',
        'Arbitration',
        'Stats',
        'DOMPDFModule',
        'ZfSnapGeoip',

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
