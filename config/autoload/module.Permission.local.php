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
            'League\Controller\TimeTable\index' => RoleInterface::ROLE_LEAGUE_MANAGER,
            'League\Controller\TimeTable\edit'  => RoleInterface::ROLE_LEAGUE_MANAGER,
            'League\Controller\TimeTable\mySchedule' => 'guest',
            'League\Controller\TimeTable\schedule' => 'guest',

            'Season\Controller\Season' => 'admin',
            'Season\Controller\Player' => 'admin',
            'Season\Controller\League' => 'admin',
            'Season\Controller\MatchDay' => 'admin',
            'Season\Controller\Schedule' => 'admin',
            'Season\Controller\Confirm\register' => 'guest',

            'Support\Controller\Manager' => RoleInterface::ROLE_LEAGUE_OWNER,
            'Support\Controller\Referee' => RoleInterface::ROLE_ADMIN,
            'Support\Controller\Support' => RoleInterface::ROLE_GUEST,
            'Support\Controller\Ticket' => RoleInterface::ROLE_USER,

            'Arbitration\Controller\Arbitration' => RoleInterface::ROLE_REFEREE,

        )
    ),
);
