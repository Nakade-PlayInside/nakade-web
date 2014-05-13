<?php
namespace Permission\Controller\Plugin;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\Permissions\Acl\Acl;

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
     * @param Acl $acl
     */
    public function setAcl(Acl $acl);

    /**
     * @return Acl
     */
    public function getAcl();

    /**
     * @param string $role
     */
    public function setRole($role);

    /**
     * @return string
     */
    public function getRole();

    /**
     * @param string $resourceAction
     *
     * @return $this
     */
    public function setResourceAction($resourceAction);

    /**
     * @return string
     */
    public function getResourceAction();

    /**
     * @param string $resourceController
     *
     * @return $this
     */
    public function setResourceController($resourceController);

    /**
     * @return string
     */
    public function getResourceController();

    /**
     * @return bool
     */
    public function hasResource();

    /**
     * @return bool
     */
    public function isAllowed();

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

