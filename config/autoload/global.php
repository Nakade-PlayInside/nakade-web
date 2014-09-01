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
            'footer_navigation' => 'Application\Navigation\FooterNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale'   => 'de_DE',
        'fallback' => 'en_US',
    ),

    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s>',
            'message_close_string'     => '</div>',
        )
    ),


    //global config key for all navigation configurations
    'navigation' => array(

        //footer
        'siteMenu' => array(
            'faq' => array(
                'label' => 'FAQ',
                'route' => 'privacy',
                'controller' => 'Application\Controller\Privacy',
                'action' => 'faq',
            ),
            'rules' => array(
                'label' => 'Rules',
                'route' => 'privacy',
                'controller' => 'Application\Controller\Privacy',
                'action' => 'rules',
            ),
            'useTerms' => array(
                'label' => 'Terms of Use',
                'route' => 'privacy',
                'controller' => 'Application\Controller\Privacy',
                'action' => 'useTerms',
            ),
            'imprint' => array(
                'label' => 'Imprint',
                'route' => 'imprint',
                'controller' => 'Application\Controller\Imprint',
                'action' => 'index',
            ),
            'contact' => array(
                'label' => 'Contact',
                'route' => 'contact',
                'controller' => 'Application\Controller\Contact',
                'action' => 'index',
            ),
            'training' => array(
                'label' => 'Location',
                'route' => 'training',
                'controller' => 'Application\Controller\Training',
                'action' => 'index',
            ),
        ),

        //not more than one subMenu level (mobile!)
        'default' => array(

            //admin
            'admin' => array(
                'label' => 'Admin',
                'route' => 'user',
                'privilege' => RoleInterface::ROLE_MODERATOR,
                'pages' => array(
                    'user' => array(
                        'label' => 'User',
                        'route' => 'user',
                        'controller' => 'User\Controller\User',
                        'action' => 'index',
                    ),
                    'coupons' => array(
                        'label' => 'Coupons',
                        'route' => 'coupon',
                        'controller' => 'User\Controller\Coupon',
                        'action' => 'moderate',
                    ),
                    'referees' => array(
                        'label' => 'Referees',
                        'route' => 'referee',
                        'controller' => 'Support\Controller\Referee',
                        'action' => 'index',
                    ),
                    'edit_result' => array(
                        'label' => 'Open Results',
                        'route' => 'result',
                        'controller' => 'League\Controller\Result',
                        'action' => 'index',
                    ),
                    'edit_match_day' => array(
                        'label' => 'Edit Match Day',
                        'route' => 'timeTable',
                        'controller' => 'League\Controller\TimeTable',
                        'action' => 'index',
                    ),
                    'appointments' => array(
                        'label' => 'Appointments',
                        'route' => 'appointmentModerator',
                        'controller' => 'Appointment\Controller\Moderator',
                        'action' => 'index',
                    ),

                )
            ),

            //home
            'home' => array(
                'label' => 'Home',
                'route' => 'home',
                'controller' => 'Application\Controller\Index',
                'action' => 'index',
            ),
            //profile
            /*   'profile' => array(
                   'label' => 'Profile',
                   'route' => 'profile',
                   'controller' => 'User\Controller\Profile',
                   'action' => 'index',
                   'privilege' => RoleInterface::ROLE_GUEST
               ),*/
            //dashboard
            'dashboard' => array(
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'controller' => 'Authentication\Controller\Dashboard',
                'action' => 'index',
                'privilege' => RoleInterface::ROLE_GUEST,
            ),

            //messages
            /*   'messages' => array(
                   'label' => 'Messages',
                   'route' => 'message',
                   'order' => 5,
                   'privilege' => RoleInterface::ROLE_GUEST,
                   'pages' => array(
                       'inbox' => array(
                           'label' => 'Inbox',
                           'route' => 'message',
                           'controller' => 'Message\Controller\Message',
                           'action' => 'index'
                       ),
                       'new_messages' => array(
                           'label' => 'New Message',
                           'route' => 'message',
                           'controller' => 'Message\Controller\Message',
                           'action' => 'new'
                       ),
                       'outbox' => array(
                           'label' => 'Outbox',
                           'route' => 'message',
                           'controller' => 'Message\Controller\Message',
                           'action' => 'outbox'
                       ),
                   )
               ),*/

            //league
            'league' => array(
                'label' => 'League',
                'route' => 'timeTable',
                'privilege' => RoleInterface::ROLE_GUEST,
                'pages' => array(
                    'schedule' => array(
                        'label' => 'Schedule',
                        'route' => 'timeTable',
                        'controller' => 'League\Controller\TimeTable',
                        'action' => 'schedule',
                    ),
                    'standings' => array(
                        'label' => 'Standings',
                        'route' => 'actual',
                        'controller' => 'League\Controller\Table',
                        'action' => 'table',
                    ),
                    'results' => array(
                        'label' => 'Enter Result',
                        'route' => 'result',
                        'controller' => 'League\Controller\Result',
                        'action' => 'myResult',
                    ),
                    'available' => array(
                        'label' => 'Shift Date',
                        'route' => 'appointmentShow',
                        'controller' => 'Appointment\Controller\Show',
                        'action' => 'available',
                    ),
                )
            ),

            //appointment
         /*   'appointment' => array(
                'label' => 'Appointment',
                'route' => 'appointmentShow',
                'privilege' => RoleInterface::ROLE_GUEST,
                'pages' => array(
                    'available' => array(
                        'label' => 'Shift Date',
                        'route' => 'appointmentShow',
                        'controller' => 'Appointment\Controller\Show',
                        'action' => 'available',
                    ),
                    'confirm' => array(
                        'label' => 'Confirm',
                        'route' => 'appointmentShow',
                        'controller' => 'Appointment\Controller\Show',
                        'action' => 'index',
                    ),
                )
            ),*/





            //support
            'support' => array(
                'label' => 'Support',
                'route' => 'support',
                'privilege' => RoleInterface::ROLE_GUEST,
                'pages' => array(
                    'tickets' => array(
                        'label' => 'My Tickets',
                        'route' => 'support',
                        'controller' => 'Support\Controller\Support',
                        'action' => 'index',
                    ),
                    'leagueManager' => array(
                        'label' => 'League Manager',
                        'route' => 'support',
                        'controller' => 'Support\Controller\Support',
                        'action' => 'add',
                        'params' => array ('id' => 2),
                    ),
                    'admin' => array(
                        'label' => 'Admin',
                        'route' => 'support',
                        'controller' => 'Support\Controller\Support',
                        'action' => 'add',
                        'params' => array ('id' => 1),
                    ),
                    'referee' => array(
                        'label' => 'Referee',
                        'route' => 'support',
                        'controller' => 'Support\Controller\Support',
                        'action' => 'add',
                        'params' => array ('id' => 3),
                    ),
                )
            ),

            //manager
            'manager' => array(
                'label' => 'Moderator',
                'route' => 'ticket',
                'privilege' => RoleInterface::ROLE_MANAGER,
                'pages' => array(
                    'new_season' => array(
                        'label' => 'New Season',
                        'route' => 'createSeason',
                        'controller' => 'Season\Controller\Season',
                        'action' => 'create',
                        'privilege' => RoleInterface::ROLE_LEAGUE_OWNER,
                    ),
                    'manager' => array(
                        'label' => 'My Manager',
                        'route' => 'manager',
                        'controller' => 'Support\Controller\Manager',
                        'action' => 'index',
                        'privilege' => RoleInterface::ROLE_LEAGUE_OWNER,
                    ),
                    'edit_match_day' => array(
                        'label' => 'Edit Match Day',
                        'route' => 'timeTable',
                        'controller' => 'League\Controller\TimeTable',
                        'action' => 'index',
                        'privilege' => RoleInterface::ROLE_LEAGUE_MANAGER,
                    ),
                    'edit_result' => array(
                        'label' => 'Edit Result',
                        'route' => 'result',
                        'controller' => 'League\Controller\Result',
                        'action' => 'allResults',
                        'privilege' => RoleInterface::ROLE_LEAGUE_MANAGER,
                    ),
                    'arbitration' => array(
                        'label' => 'Arbitration',
                        'route' => 'arbitration',
                        'controller' => 'Arbitration\Controller\Arbitration',
                        'action' => 'index',
                        'privilege' => RoleInterface::ROLE_REFEREE,
                    ),
                    'tickets' => array(
                        'label' => 'Tickets',
                        'route' => 'ticket',
                        'controller' => 'Support\Controller\Ticket',
                        'action' => 'index',
                        'privilege' => RoleInterface::ROLE_MANAGER,
                    ),

                )
            ),

            //logOut
            'logOut' => array(
                'label' => 'Logout',
                'route' => 'login',
                'controller' => 'Authentication\Controller\Authentication',
                'action' => 'logout',
                'order' => 100,
                'privilege' => RoleInterface::ROLE_GUEST,
            ),
            //more
        ),




    ),

);

