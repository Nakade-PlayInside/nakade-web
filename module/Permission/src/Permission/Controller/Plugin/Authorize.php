<?php
namespace Permission\Controller\Plugin;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Authorize
 *
 * @package Permission\Controller\Plugin
 *
 */
class Authorize extends AbstractPermission implements AuthorizeInterface
{

    /**
     * @param MvcEvent $event
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function doAuthorization(MvcEvent $event)
    {
        $this->event = $event;
        $this->serviceManager = $event->getApplication()->getServiceManager();


        $aclService = $this->getService('Permission\Services\AclService');
        $authService = $this->getService('Zend\Authentication\AuthenticationService');

        $role = $this->getRoleByIdentity($authService);

        $resource = $this->getRequestedResource();

        /* @var $acl \Zend\Permissions\Acl\Acl */
        $acl = $aclService->getAcl();

        //unknown resource is free to everyone
        if (!$acl->hasResource($resource)) {
            return $event->getResponse();
        }

        //not logged in
        if ($role === self::DEFAULT_ROLE) {
            return $this->redirectToRoute('login');
        }

        //look for action; if allowed return response

        //look for controller
        if (!$acl->isAllowed($role, $resource)) {
            return $this->redirectToRoute('forbidden');
        }

        return $event->getResponse();
    }


    /**
     * @return string
     */
    private function getRequestedResource()
    {
        $event = $this->getEvent();

        $routeMatch     = $event->getRouteMatch();
        $controller     = $routeMatch->getParam('controller');
        $action         = $routeMatch->getParam('action');

        //var_dump($action);die;
        //the resource to request
        //$requestedResource = $controller . "\\" . $action;
        $requestedResource = $controller;

        return $requestedResource;

    }



}

