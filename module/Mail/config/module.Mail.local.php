<?php
/**
 * This is a sample "local" configuration for your mail config. 
 * Copy it to your config/autoload/ directory of your application, 
 * and edit to suit your application.
 */
return array(
    'nakade_mail' => array(
       
        // This sets the default "to" 
        'message' => array(
            'from' => 'name@domain.org' , 
            'name' => 'your name (opt.)',
            'reply'=> 'name@domain.org (opt.)',
        ),

        // Transport consists of two keys: 
        // - "method", => 'sendmail', 'smtp', 'file'
        // - "options" => required for 'smtp' and 'file'
        'transport' => array(
            'method'   => 'smtp',
            'options' => array(
                'host'             => 'smtp.gmail.com',
                'port'             => 587,
                'connectionClass'  => 'login',
                'connectionConfig' => array(
                    'ssl'      => 'tls',
                    'username' => 'contact@your.tld',
                    'password' => 'password',
                ),
            ),
        ),
    ),
);
