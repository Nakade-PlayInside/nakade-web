<?php
/**
 * This is a sample "local" configuration for your mail config.
 * Copy it to your config/autoload/ directory of your application,
 * and edit to suit your application.
 */
return array(
    'nakade_mail' => array(

        // This sets the default "to" and "sender" headers for your message
        'message' => array(
            'from' => 'holger@nakade.de' ,
            'name' => 'Nakade Mail',
            'reply'=> 'noreply@nakade.de',
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
                    'username' => 'grrompf@gmail.com',
                    'password' => 'kMdCS2013',
                ),
            ),
        ),

        'signature' => array(
            'team'  => 'Nakade Team',
            'club'  => 'Berliner Baduk Club e.V.',
            'register_court' => 'Berlin-Charlottenburg',
            'register_no'    => 'VR31852',
        ),
    ),
);
