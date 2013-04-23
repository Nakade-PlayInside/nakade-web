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
        'text_domain' => 'Auth',
        'max_auth_attempts' => 5,
        'captcha' => array(
        //google ReCaptcha data
        //global-key.spandaugo.de
         'class'   => 'recaptcha',
            'options' => array(
                'pubkey'  => '6Lcc-9kSAAAAACM-dY-fAFeY9qMfFNhdV2M-DZ3_',
                'privkey' => '6Lcc-9kSAAAAAHQ3sXqzQGLQ1ujIsWj5u_rrdr5B',
            ),
        ),
        
    ),
);
