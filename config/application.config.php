<?php
return array(
    'modules' => array(
        'Application',
        'Training',
        //'PhlyContact',
        'Impressum',
        //'Kontakt',
        //'Album',
        
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
