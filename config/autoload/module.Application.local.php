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
    'db' => array(
        'dsn'      => 'mysql:dbname=bbc-blog;host=localhost',
        'username' => 'wordpress',
        'password' => 'j7F9wMWKLdXEJTbP',
    ),

    'Application' => array(

        //your text domain for translation
        'text_domain' => 'Application',

        'contact' => array (
            'captcha' => array(
                'class'   => 'recaptcha',
                'options' => array(
                    'pubkey'  => '6Lcc-9kSAAAAACM-dY-fAFeY9qMfFNhdV2M-DZ3_',
                    'privkey' => '6Lcc-9kSAAAAAHQ3sXqzQGLQ1ujIsWj5u_rrdr5B',
                ),
            ),

            //copies form contact mail
            'bbc' => array (
                'grrompf@gmail.com',
                'holger.maerz@docholiday-online.de',
                'holger@spandaugo.de'
            ),
        ),
    ),
);
