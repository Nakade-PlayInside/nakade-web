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
            // more helpers here ...
        )
    ),

    'controllers' => array(
        'factories' => array(
            'League\Controller\ActualSeason' =>
                    'League\Services\ActualSeasonControllerFactory',
            'League\Controller\Result' =>
                    'League\Services\ResultControllerFactory',
            'League\Controller\MatchDay' =>
                    'League\Services\MatchDayControllerFactory',
        ),

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
                        'controller' => 'League\Controller\ActualSeason',
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

            'matchDay' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/matchDay[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'League\Controller\MatchDay',
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
