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
    
    'NakadeAuth' => array(
        
        //your text domain for translation
        'text_domain' => 'Auth',
        
        //example google ReCaptcha data
        'captcha' => array(
            'class'   => 'recaptcha',
            'options' => array(
                'pubkey'  => 'PUT YOUR PUBLIC KEY HERE',
                'privkey' => 'PUT YOUR PRIVATE KEY HERE',
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
