<?php
/**
 * This is a sample "local" configuration for your application. To use it, copy
 * it to your config/autoload/ directory of your application, and edit to suit
 * your application.
  */
return array(

    'Permission' => array(

        //your text domain for translation
        'text_domain' => 'Permission',

        //resources for permission control
        'resources' => array (
            'nav-admin' => 'admin',
            'Authentication\Controller\Dashboard' => 'guest',

            'User\Controller\User' => 'admin',
            'User\Controller\Profile' => 'guest',

            'Appointment\Controller\Appointment' => 'guest',
            'Appointment\Controller\Show' => 'guest',
            'Appointment\Controller\Show\reject' => 'moderator',

            'Message\Controller\Message' => 'guest',

            'Authentication\Controller\DashboardController' => 'guest',

            'League\Controller\Table\table' => 'guest',
            'League\Controller\Result\add' => 'guest',
            'League\Controller\Result\myResult' => 'guest',
            'League\Controller\Result\index' => 'admin',
            'League\Controller\TimeTable\index' => 'admin',
            'League\Controller\TimeTable\edit' => 'admin',
            'League\Controller\TimeTable\mySchedule' => 'guest',
            'League\Controller\TimeTable\schedule' => 'guest',

            'Season\Controller\Season' => 'admin',
            'Season\Controller\Player' => 'admin',
            'Season\Controller\League' => 'admin',
            'Season\Controller\MatchDay' => 'admin',
            'Season\Controller\Schedule' => 'admin',
            'Season\Controller\Confirm\register' => 'guest',

        )
    ),
);
