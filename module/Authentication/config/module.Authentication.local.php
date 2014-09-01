<?php
/**
 * module.config.local
 */
return array(

    'NakadeAuth' => array(

        //your text domain for translation
        'text_domain' => 'Auth',

        //show captcha after n failed attempts
        'max_auth_attempts' => 5,
    ),

    'session_config' => array(
        'remember_me_seconds' => 1209600, //14d
        'use_cookies' => true,
        'cookie_httponly' => true,
        'name'                => 'NAKADE_SESSION_ID',

    ),
);
