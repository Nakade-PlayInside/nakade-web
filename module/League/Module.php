<?php

namespace League;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

/**
 * Class Module
 *
 * @package League
 */
class Module implements AutoloaderProviderInterface,
    ConfigProviderInterface,
    ConsoleUsageProviderInterface
{
    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @param Console $console
     *
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        return array(
            // Describe available commands
            'appointmentReminder' => 'send appointment reminder before a match',
            'autoResult' => 'automatic result of overdue open matches',
            'cleanAppointmentReminder' => 'remove appointment reminder after a match',
            'cleanResultReminder' => 'remove result reminder after a match',
            'cleanMatchReminder' => 'remove match reminder after a match',
            'createResultReminder' => 'creating next reminder until result is provided',
            'matchReminder' => 'send reminder before a match',
            'resultReminder' => 'send reminder for open matches',

        );
    }

}
