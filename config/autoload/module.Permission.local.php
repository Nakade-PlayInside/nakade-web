<?php
use Permission\Entity\RoleInterface;

return array(

    'Permission' => array(

        //your text domain for translation
        'text_domain' => 'Permission',

        //resources for permission control
        'resources' => array (
            'nav-admin' => 'admin',
            'Authentication\Controller\Dashboard' => RoleInterface::ROLE_GUEST,

            'User\Controller\User' => RoleInterface::ROLE_ADMIN,
            'User\Controller\Profile' => RoleInterface::ROLE_GUEST,
            'User\Controller\Coupon' => RoleInterface::ROLE_GUEST,
            'User\Controller\Coupon\moderate' => RoleInterface::ROLE_ADMIN,
            'User\Controller\Coupon\inactivate' => RoleInterface::ROLE_ADMIN,

            'Appointment\Controller\Appointment' => RoleInterface::ROLE_GUEST,
            'Appointment\Controller\Show' => RoleInterface::ROLE_GUEST,
            'Appointment\Controller\Show\reject' => 'moderator',
            'Appointment\Controller\Moderator' => 'moderator',

            'Message\Controller\Message' => RoleInterface::ROLE_GUEST,

            'League\Controller\Table\table' => RoleInterface::ROLE_GUEST,
            'League\Controller\Table\detailed' => RoleInterface::ROLE_GUEST,
            'League\Controller\Result\add' => RoleInterface::ROLE_GUEST,
            'League\Controller\Result\myResult' => RoleInterface::ROLE_GUEST,
            'League\Controller\Result\index' => RoleInterface::ROLE_ADMIN,
            'League\Controller\Result\allResults' => RoleInterface::ROLE_LEAGUE_MANAGER,
            'League\Controller\Result\edit' => RoleInterface::ROLE_LEAGUE_MANAGER,
            'League\Controller\TimeTable\index' => RoleInterface::ROLE_LEAGUE_MANAGER,
            'League\Controller\TimeTable\edit'  => RoleInterface::ROLE_LEAGUE_MANAGER,
            'League\Controller\TimeTable\mySchedule' => RoleInterface::ROLE_GUEST,
            'League\Controller\TimeTable\schedule' => RoleInterface::ROLE_GUEST,

            'Season\Controller\Season' => 'admin',
            'Season\Controller\Player' => 'admin',
            'Season\Controller\League' => 'admin',
            'Season\Controller\MatchDay' => 'admin',
            'Season\Controller\Schedule' => 'admin',
            'Season\Controller\Confirm\register' => RoleInterface::ROLE_GUEST,

            'Support\Controller\Manager' => RoleInterface::ROLE_LEAGUE_OWNER,
            'Support\Controller\Referee' => RoleInterface::ROLE_MODERATOR,
            'Support\Controller\Support' => RoleInterface::ROLE_GUEST,
            'Support\Controller\Ticket' => RoleInterface::ROLE_MANAGER, //LM, admin, owner, refs
            'Support\Controller\Feature' => RoleInterface::ROLE_GUEST,

            'Arbitration\Controller\Arbitration' => RoleInterface::ROLE_REFEREE,

            'Stats\Controller\Index' => RoleInterface::ROLE_GUEST,
            'Certificate\Controller\Index' => RoleInterface::ROLE_GUEST,



        )
    ),
);
