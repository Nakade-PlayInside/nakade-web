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

    'League' => array(

        //time to start reminding after a match
        'result_reminder_time' => 8,

        //time before a match to remind
        'match_reminder_time' => 48,

        //time before making an automatic result
        'auto_result_time' => 96,

        //your text domain for translation
        'text_domain' => 'League',
    ),
);
