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

    'Application' => array(

        //your text domain for translation
        'text_domain' => 'Application',

        'contact' => array (

            //copies form contact mail
            'bbc' => array (
                'grrompf@gmail.com',
                'tina@nakade.de',
                'mo@nakade.de'
            ),
        ),
    ),
);
