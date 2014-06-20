<?php
/**
 * The config information is passed to the relevant components by the
 * ServiceManager. The controllers section provides a list of all the
 * controllers provided by the module.
 *
 * Within the view_manager section, we add our view directory to the
 * TemplatePathStack configuration.
 *
 * @return array
 */
namespace League;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'isWinner'   => 'League\View\Helper\Winner',
            'result'     => 'League\View\Helper\Result',
            'hasResult' => 'League\View\Helper\HasResult',
            'highlightMatch' => 'League\View\Helper\HighlightMatch',
            'highlightUser'  => 'League\View\Helper\HighlightUser',
            'myColor'  => 'League\View\Helper\MyColor',
            'opponent'  => 'League\View\Helper\Opponent',
            'openResult'  => 'League\View\Helper\OpenResult',
            'sort'  => 'League\View\Helper\Sort',
            'isOpen'     => 'League\View\Helper\Open',
            'matchDayResult'     => 'League\View\Helper\MatchDayResult',
            'pagingUrl'     => 'League\View\Helper\PagingUrl',
            // more helpers here ...
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'League\Command\MatchReminder' => 'League\Command\MatchReminderController',
            'League\Command\ResultReminder' => 'League\Command\ResultReminderController',
            'League\Command\AutoResult' => 'League\Command\AutoResultController',
        ),
        'factories' => array(
            'League\Controller\Table' =>
                    'League\Services\TableControllerFactory',
            'League\Controller\Result' =>
                    'League\Services\ResultControllerFactory',
            'League\Controller\TimeTable' =>
                    'League\Services\TimeTableControllerFactory',
        ),

    ),

    //command
    'console' => array(
        'router' => array(
            'routes' => array(
                'matchReminder' => array(
                    'options' => array(
                        'route' => 'matchReminder',
                        'defaults' => array(
                            '__NAMESPACE__' => 'League\Command',
                            'controller' => 'League\Command\MatchReminder',
                            'action' => 'do'
                        ),
                    ),
                ),
                'resultReminder' => array(
                    'options' => array(
                        'route' => 'resultReminder',
                        'defaults' => array(
                            '__NAMESPACE__' => 'League\Command',
                            'controller' => 'League\Command\ResultReminder',
                            'action' => 'do'
                        ),
                    ),
                ),
                'autoResult' => array(
                    'options' => array(
                        'route' => 'autoResult',
                        'defaults' => array(
                            '__NAMESPACE__' => 'League\Command',
                            'controller' => 'League\Command\AutoResult',
                            'action' => 'do'
                        ),
                    ),
                ),
            )
        )
    ),

    'router' => array(
        'routes' => array(

            //actual season
            'actual' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/actual[/:action][/:sort]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'sort'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Table',
                        'action'     => 'index',
                    ),
                ),

            ),

            'result' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/result[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\Result',
                        'action'     => 'index',
                    ),
                ),
            ),

            'timeTable' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/timeTable[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\TimeTable',
                        'action'     => 'index',
                    ),
                ),
            ),
           //next route

        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
                'Shared' => __DIR__ . '/../view/partial/paginator.phtml', // Note: the key is optional
        ),

        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'League\Services\RepositoryService' =>
                'League\Services\RepositoryService',
            'League\Services\ICalService' =>
                'League\Services\ICalService',
            'League\Services\LeagueFormService' =>
                'League\Services\LeagueFormService',
            'League\Services\ResultService' =>
                'League\Services\ResultService',
            'League\Services\TableService' =>
                'League\Services\TableService',
            'League\Services\MailService' =>
                'League\Services\MailService',
            'League\Services\MatchVoterService' =>
                'League\Services\MatchVoterService',
            'League\Services\PaginationService' =>
                'League\Services\PaginationService',
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'League',
            ),
        ),
    ),

    //Doctrine2
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
           ),
           'orm_default' => array(
               'drivers' => array(
                __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
               )
           )
        )
    ),

);
