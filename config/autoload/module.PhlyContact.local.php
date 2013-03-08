<?php
/**
 * This is a sample "local" configuration for your application. To use it, copy 
 * it to your config/autoload/ directory of your application, and edit to suit
 * your application.
 *
 * This configuration example demonstrates using an SMTP mail transport, a
 * ReCaptcha CAPTCHA adapter, and setting the to and sender addresses for the
 * mail message.
 */
return array(
    'phly_contact' => array(
        // This is simply configuration to pass to Zend\Captcha\Factory
        //global-key.spandaugo.de

        'captcha' => array(
            'class'   => 'recaptcha',
            'options' => array(
                'pubkey'  => '6Lcc-9kSAAAAACM-dY-fAFeY9qMfFNhdV2M-DZ3_',
                'privkey' => '6Lcc-9kSAAAAAHQ3sXqzQGLQ1ujIsWj5u_rrdr5B',
            ),
        ),

        // This sets the default "to" and "sender" headers for your message
        'message' => array(
            
            // These can be either a string, or an array of email => name pairs
            //email adresse an die die nachricht geht
            'to'     => 'grrompf@gmail.com',
            
            //Pflichtfeld oder Eintrag in php.ini
            'from'   => 'holger@nakade.de',
            // This should be an array with minimally an "address" element, and 
            // can also contain a "name" element
           'sender' => array(
                'address' => 'tina@nakade.de'
            ),
             
        ),

        // Transport consists of two keys: 
        // - "class", the mail tranport class to use, and
        // - "options", any options to use to configure the 
        //   tranpsort. Usually these will be passed to the 
        //   transport-specific options class
        // This example configures GMail as your SMTP server
        'mail_transport' => array(
            'class'   => 'Zend\Mail\Transport\Smtp',
            'options' => array(
                'host'             => 'smtp.gmail.com',
                'port'             => 587,
                'connectionClass'  => 'login',
                'connectionConfig' => array(
                    'ssl'      => 'tls',
                    'username' => 'grrompf@gmail.com',
                    'password' => 'holger27',
                ),
            ),
        ),
    ),
);
