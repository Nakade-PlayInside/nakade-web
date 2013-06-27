<?php
namespace Permission\Controller\Plugin;
  
use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Mvc\MvcEvent,    
    Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Authentication\AuthenticationService;


/**
 * Authorization PlugIn using ACL, Roles and Ressources.
 * A ressource is not restricted, it is free for everyone by default.
 * Otherwise if not signed in, user will be redirected to login.
 * If not authorized, you will get a 403 error message.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class Authorize extends AbstractPlugin
{
    
    protected $_event;
    protected $_authentication_service;
    protected $_default_role = 'everyone';//unsigned user
 
    /**
     * get the authentication service
     */
    private function createService() 
    {
        $event = $this->getEvent();
        
         //set authentication service
        $manager = $event->getApplication()->getServiceManager();
        
        if($manager->has('Zend\Authentication\AuthenticationService')) {
            $authService = 
                $manager->get('Zend\Authentication\AuthenticationService');
            $this->setAuthenticationService($authService);
        }
    }
    
    /**
     * get event
     * 
     * @return MvcEvent
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * set event
     * 
     * @param MvcEvent $event
     */
    public function setEvent(MvcEvent $event)
    {
        $this->_event = $event;
    }

    
    
    /**
     * get AuthServcice
     * 
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->_authentication_service;
    }

    /**
     *  set AuthServcice
     * 
     * @param AuthenticationService $authenticationService
     */
    public function setAuthenticationService(AuthenticationService $authenticationService)
    {
        $this->_authentication_service = $authenticationService;
    }

    /**
     * get Identity if signed in
     * 
     * @return null|Identity
     */
    private function getIdentity() 
    {
        if ($this->_authentication_service===null) {
            return null;
        }
        
        //set identity
        if (!$this->_authentication_service->hasIdentity()) {
            return null;
        }
        
        return $this->_authentication_service->getIdentity();
    }
    
    /**
     * if signed in return true
     * 
     * @return bool
     */
    public function hasIdentity() 
    {
        $identity = $this->getIdentity();
        return isset($identity);
    }
 
    /**
     * get hardcoded ACL, its roles and ressources.
     * edit this method for your need
     *  
     * @return \Zend\Permissions\Acl\Acl
     */
    private function getAcl()
    {
        //setting ACL...
        $acl = new Acl();
        
        //add roles ..
        $acl->addRole(new Role($this->_default_role)); 
        $acl->addRole(new Role('guest'),  $this->_default_role);
        $acl->addRole(new Role('user'),  'guest');
        $acl->addRole(new Role('member'),  'guest');
        $acl->addRole(new Role('moderator'),  'member');
        $acl->addRole(new Role('admin'), 'moderator');
      
        /**
         * module => namespace ; 
         * controller => controller namespace eg 'User\Controller\User'
         * action => eg 'edit'
         * 
         * you have to proof against the given ressource when using isAllowed
         */
        
        //add ressources  
        $acl->addResource(new Resource('User\Controller\User'));
        $acl->allow('admin', 'User\Controller\User' );
        
        $acl->addResource(new Resource('User\Controller\Profile'));
        $acl->allow('guest', 'User\Controller\Profile' );
        
        return $acl;
    }
    
    /**
     * returns user's role from identity if signed in, otherwise
     * default role is returned by default.
     * 
     * @return string
     */
    private function getRole()
    {
        
        $identity   = $this->getIdentity();
        if($identity === null) {   
            return  $this->_default_role;
        }    
           
        if(method_exists($identity, getRole)) {
             
             $temp = $identity->getRole(); 
             $role = empty($temp) ? $this->_default_role : $temp; 
             return $role;
        }
        return $this->_default_role;
        
    }
   
    /**
     * get the actual ressource from the request
     * 
     * @return string
     */
    private function getRessource()
    {
        $event = $this->getEvent();
        
        /*
        //get namespace -> module
        $controller = $event->getTarget();
        $controllerClass = get_class($controller);
        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        */
        
        $routeMatch     = $event->getRouteMatch(); 
        $controller     = $routeMatch->getParam('controller');
        //$action         = $routeMatch->getParam('action');
    
        //the ressouce to request 
        $requestedRessouce = $controller;
        //$requestedRessouce = $controller . "\\" . $action; 
        
        return $requestedRessouce;
    }
  
    /**
     * set the redirection and stops the propagation
     * 
     * @param string $route
     */
    private function redirectToRoute($route)
    {
        $event = $this->getEvent();
        $router = $event->getRouter();
        $url    = $router->assemble(array(), array('name' => $route));
         
        $response = $event->getResponse();
        $response->setStatusCode(302);
        
        //redirect to login route...
        /* change with header('location: '.$url); if code below not working */
        $response->getHeaders()->addHeaderLine('Location', $url);
        $event->stopPropagation();
        
    }
    
    /**
     * Proves authorization. If ressource is not restricted, do nothing.
     * Otherwise proves authentication and permission.  
     * 
     * @param \Zend\Mvc\MvcEvent $event
     * @return mixed
     */
    public function doAuthorization(MvcEvent $event)
    {
        $this->setEvent($event);
        
        $this->createService();
        $role = $this->getRole();
        $acl = $this->getAcl(); 
        
        $requestedRessouce = $this->getRessource();     
        
        //unknown ressource is free to everyone
        if( ! $acl->hasResource($requestedRessouce)) 
            return;
        
        if ( ! $this->hasIdentity()) {
            return $this->redirectToRoute('login');
        }
        
        if ( ! $acl->isAllowed($role, $requestedRessouce)) {
            return $this->redirectToRoute('forbidden');
        }
    }
}

?>
