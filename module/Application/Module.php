<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication
 * for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc.
 * (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;

// Add these import statements:
use Blog\Model\Blog;
use Blog\Model\BlogTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

class Module
{
    /**
     * @param mixed $events
     */
    public function onBootstrap($events)
    {

        /* @var $translator \Zend\I18n\Translator\Translator */
        $translator = $events->getApplication()->getServiceManager()->get(
            'translator'
        );

        $authService = $events->getApplication()->getServiceManager()->get('Zend\Authentication\AuthenticationService');
        $locale = $this->getLocale($authService);

        $translator->setLocale(
            \Locale::acceptFromHttp($locale)
        );

        $eventManager        = $events->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

    }

    private function getLocale(AuthenticationService $authService)
    {
        $locale = $this->getLocaleFromIdentity($authService);
        if (is_null($locale)) {
           $locale = $this->getLocaleByBrowser();
        }
        return $locale;

    }

    private function getLocaleFromIdentity(AuthenticationService $authService)
    {
        if (!$authService->hasIdentity()) {
            return null;
        }

        /* @var $user \User\Entity\User */
        $user = $authService->getIdentity();
        return $user->getLanguage();

    }

    private function getLocaleByBrowser()
    {
        $locale = "en_US"; //default
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $locale=$this->getLocaleByBrowserConfig($languages);
        }
        return $locale;
    }

    private function getLocaleByBrowserConfig(array $languages)
    {
        $lang = $languages[0];
        if (strpos($lang, "de") === 0) {
            return "de_DE";
        }
        return $lang;
    }


    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
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
    public function getServiceConfig()
    {
        return array(
            'factories' => array(

                'Application\Model\BlogTable' =>  function($serviceManager) {
                    $tableGateway = $serviceManager->get('BlogTableGateway');
                    $table = new BlogTable($tableGateway);
                    return $table;
                },
                'BlogTableGateway' => function ($serviceManager) {
                    $dbAdapter =
                        $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Blog());
                    return new TableGateway(
                        'wp_posts', $dbAdapter, null, $resultSetPrototype
                    );
                },
            ),
        );
    }
}
