<?php
/**
 * This is a sample "local" configuration for your application. To use it, copy
 * it to your config/autoload/ directory of your application, and edit to suit
 * your application.
  */
return array(

    'Permission' => array(

        //your text domain for translation
     //   'text_domain' => 'Permission',

        //resources for permission control
        'resources' => array ('nav-admin' => 'admin',
            'User\Controller\User' => 'admin',
            'User\Controller\Profile' => 'guest',
            'Appointment\Controller\Appointment' => 'guest',
            'Appointment\Controller\Show' => 'guest',
            'Messages\Controller\Messages' => 'guest',
        )
    ),
);
