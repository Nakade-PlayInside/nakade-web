<?php

use Permission\Entity\RoleInterface;
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

// config/autoload/global.php:
return array(

    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'app_navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale'   => 'de_DE',
        'fallback' => 'en_US',
    ),


    //global config key for all navigation configurations
    'navigation' => array(

        //not more than one subMenu level (mobile!)
        'default' => array(

            //home
            'home' => array(
                'label' => 'Home',
                'route' => 'home',
            ),
            //profile
            'profile' => array(
                'label' => 'Profile',
                'route' => 'profile',
                'privilege' => RoleInterface::ROLE_GUEST
            ),
            //dashboard
            'dashboard' => array(
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'controller' => 'Authentication\Controller\Dashboard',
                'action' => 'index',
                'privilege' => RoleInterface::ROLE_GUEST,
                'order' => 1,
            ),

            //messages
            'messages' => array(
                'label' => 'Messages',
                'route' => 'message',
                'order' => 5,
                'privilege' => RoleInterface::ROLE_GUEST,
                'pages' => array(
                    'inbox' => array(
                        'label' => 'Inbox',
                        'route' => 'message',
                    ),
                    'new_messages' => array(
                        'label' => 'New Message',
                        'route' => 'message',
                        'action' => 'new'
                    ),
                    'outbox' => array(
                        'label' => 'Outbox',
                        'route' => 'message',
                        'action' => 'outbox'
                    ),
                )
            ),

            //league
            'league' => array(
                'label' => 'League',
                'route' => 'timeTable',
                'action' => 'schedule',
                'privilege' => RoleInterface::ROLE_GUEST,
                'order' => 6,
                'pages' => array(
                    'schedule' => array(
                        'label' => 'Schedule',
                        'route' => 'timeTable',
                        'action' => 'schedule',
                        'order' => 1,
                    ),
                    'standings' => array(
                        'label' => 'Standings',
                        'route' => 'actual',
                        'action' => 'table',
                        'order' => 2,
                    ),
                    'results' => array(
                        'label' => 'Results',
                        'route' => 'result',
                        'action' => 'myResult',
                        'order' => 3,
                    ),
                )
            ),
            //manager
            'manager' => array(
                'label' => 'Moderator',
                'route' => 'ticket',
                'privilege' => RoleInterface::ROLE_MANAGER,
                'order' => 3,
                'pages' => array(
                    'new_season' => array(
                        'label' => 'New Season',
                        'route' => 'createSeason',
                        'action' => 'create',
                        'privilege' => RoleInterface::ROLE_LEAGUE_OWNER,
                        'order' => 1,
                    ),
                    'manager' => array(
                        'label' => 'My Manager',
                        'route' => 'manager',
                        'privilege' => RoleInterface::ROLE_LEAGUE_OWNER,
                        'order' => 2,
                    ),
                    'edit_match_day' => array(
                        'label' => 'Edit Match Day',
                        'route' => 'timeTable',
                        'privilege' => RoleInterface::ROLE_LEAGUE_MANAGER,
                        'order' => 3,
                    ),
                    'edit_result' => array(
                        'label' => 'Edit Result',
                        'route' => 'result',
                        'action' => 'allResults',
                        'privilege' => RoleInterface::ROLE_LEAGUE_MANAGER,
                        'order' => 4,
                    ),
                    'arbitration' => array(
                        'label' => 'Arbitration',
                        'route' => 'arbitration',
                        'privilege' => RoleInterface::ROLE_REFEREE,
                    ),

                )
            ),

            //admin
            'admin' => array(
                'label' => 'Admin',
                'route' => 'user',
                'order' => 2,
                'privilege' => RoleInterface::ROLE_MODERATOR,
                'pages' => array(
                    'user' => array(
                        'label' => 'User',
                        'route' => 'user',
                        'order' => 1,
                    ),
                    'coupons' => array(
                        'label' => 'Coupons',
                        'route' => 'coupon',
                        'action' => 'moderate',
                        'order' => 2,
                    ),
                    'referees' => array(
                        'label' => 'Referees',
                        'route' => 'referee',
                        'order' => 3,
                    ),
                    'edit_result' => array(
                        'label' => 'Open Results',
                        'route' => 'result',
                        'order' => 4,
                    ),
                    'edit_match_day' => array(
                        'label' => 'Edit Match Day',
                        'route' => 'timeTable',
                    ),
                    'appointments' => array(
                        'label' => 'Appointments',
                        'route' => 'appointmentModerator',
                    ),

                )
            ),

            //support
            'support' => array(
                'label' => 'Support',
                'route' => 'support',
                'order' => 8,
                'privilege' => RoleInterface::ROLE_GUEST,
                'pages' => array(
                    'tickets' => array(
                        'label' => 'My Tickets',
                        'route' => 'support',
                        'order' => 1,
                    ),
                    'leagueManager' => array(
                        'label' => 'League Manager',
                        'route' => 'support',
                        'params' => array ('action' => 'add', 'id' => 2),
                        'order' => 2,
                    ),
                    'admin' => array(
                        'label' => 'Admin',
                        'route' => 'support',
                        'params' => array ('action' => 'add', 'id' => 1),
                        'order' => 3,
                    ),
                    'referee' => array(
                        'label' => 'Referee',
                        'route' => 'support',
                        'params' => array ('action' => 'add', 'id' => 3),
                        'order' => 4,
                    ),
                )
            ),

            //logOut
            'logOut' => array(
                'label' => 'Logout',
                'route' => 'login',
                'params' => array("action" => 'logout'),
                'order' => 100,
                'privilege' => RoleInterface::ROLE_GUEST,
            ),
            //more
        ),




    ),

);
