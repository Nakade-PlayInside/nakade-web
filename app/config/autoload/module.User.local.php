<?php

return array(
    'db_user' => array(
        'dsn'      => 'mysql:dbname=zf2tutorial;host=localhost',
        'username' => 'root',
        'password' => 'holger27',
    ),
    'User' => array(
        //your text domain for translation
        'text_domain' => 'User',
        
        'email_options' => array(
            'expire'         => '48',//period for valid email verification in hours
            'prefix'         => 'Nakade',
            'signature'      => 'Nakade Team',
            'club'           => 'Berliner Baduk Club e.V.',
            'register_court' => 'Berlin-Charlottenburg',
            'register_no'    => 'VR31852',
         ),   
    ),

);
