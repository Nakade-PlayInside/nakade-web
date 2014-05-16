<?php

return array(
    'db' => array(
        'dsn'      => 'DNS',
        'username' => 'ACCOUNT',
        'password' => 'PWD',
    ),

    'Application' => array(

        //your text domain for translation
        'text_domain' => 'Application',

        'contact' => array (
            'captcha' => array(
                'class'   => 'dumb',
            ),

            //copies form contact mail
            'bbc' => array (
                'NAME@MAIL.COM',

            ),
        ),
    ),
);
