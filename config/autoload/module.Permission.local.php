<?php
use Permission\Entity\RoleInterface;

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
            'User\Controller\Coupon' => 'guest',
            'User\Controller\Coupon\moderate' => 'admin',
            'User\Controller\Coupon\inactivate' => 'admin',

            'Appointment\Controller\Appointment' => 'guest',
            'Appointment\Controller\Show' => 'guest',
            'Appointment\Controller\Show\reject' => 'moderator',
            'Appointment\Controller\Moderator' => 'moderator',

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

            'Support\Controller\Manager' => RoleInterface::ROLE_LEAGUE_OWNER,
            'Support\Controller\Support' => 'guest',
            'Support\Controller\LeagueManager' => 'user',

        )
    ),
);
