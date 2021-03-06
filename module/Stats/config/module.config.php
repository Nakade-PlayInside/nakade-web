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
namespace Stats;

return array(

    'view_helpers' => array(
        'invokables' => array(
            'achievementTitle'   => 'Stats\View\Helper\AchievementTitle',
            'achievementImg'   => 'Stats\View\Helper\AchievementImg',
            // more helpers here ...
        )
    ),

    'controllers' => array(
        'factories' => array(
            'Stats\Controller\Index' =>
                    'Stats\Services\IndexControllerFactory',

        ),
    ),


    'router' => array(
        'routes' => array(

            'stats' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/stats[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                     ),
                    'defaults' => array(
                        'controller' => 'Stats\Controller\Index',
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
            'matches' => __DIR__ . '/../view/partial/pagination.phtml', // Note: the key is optional
            'tournament' => __DIR__ . '/../view/partial/tournament.phtml', // Note: the key is optional
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),

    ),

    'service_manager' => array(
        'factories' => array(
            'Stats\Services\RepositoryService'      =>
                'Stats\Services\RepositoryService',
            'Nakade\Services\PlayersTableService' =>
                'Nakade\Services\PlayersTableService',
            'Stats\Services\PlayerStatsService' =>
                'Stats\Services\PlayerStatsService',
            'Stats\Services\AchievementService' =>
                'Stats\Services\AchievementService',
            'Stats\Services\CrossTableService' =>
                'Stats\Services\CrossTableService',
            'Stats\Services\CertificateService' =>
                'Stats\Services\CertificateService',
            'Nakade\Webservice\EGDService' =>
                'Nakade\Webservice\EGDService',
        ),
    ),

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'          => 'gettext',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s.mo',
                'text_domain'   => 'Stats',
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
