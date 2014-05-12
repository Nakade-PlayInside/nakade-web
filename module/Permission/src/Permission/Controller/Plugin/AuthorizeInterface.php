<?php
namespace Permission\Controller\Plugin;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Interface AuthorizeInterface
 *
 * @package Permission\Controller\Plugin
 */
interface AuthorizeInterface
{

    /**
     * @param MvcEvent $event
     *
     * @return mixed
     */
    public function doAuthorization(MvcEvent $event);


    /**
     * get event
     *
     * @return MvcEvent
     */
    public function getEvent();

    /**
     * @param MvcEvent $event
     */
    public function setEvent(MvcEvent $event);


    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceManager();

    /**
     * @param ServiceLocatorInterface $serviceManager
     *
     * @return $this
     */
    public function setServiceManager(ServiceLocatorInterface $serviceManager);

    /**
    * @param string $route
    *
    * @return \Zend\Stdlib\ResponseInterface
    */
    public function redirectToRoute($route);

}

