<?php
namespace Permission\Controller\Plugin;
  
use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Mvc\MvcEvent,    
    Zend\Permissions\Acl\Resource\GenericResource as Resource;


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
    protected $_role;
    protected $_ressource;
    protected $_identity;
    protected $_acl;
    protected $_default_role = 'everyone';//unsigned user
 
    /**
     * get default role
     * 
     * @return string
     */
    public function getDefaultRole()
    {
        return $this->_default_role;
    }
    
    /**
     * get role
     * 
     * @return string
     */
    public function getRole()
    {
        if(null === $this->_role) {
            $this->initRole();
        }
        return $this->_role;
    }

    /**
     * set role
     * 
     * @param string $role
     */
    public function setRole($role)
    {
        $this->_role = $role;
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
     * get Acl
     * 
     * @return Acl
     */
    public function getAcl()
    {
        if(null === $this->_acl) {
            $this->initAcl();
        }
        
        return $this->_acl;
    }

    /**
     * set Acl
     * 
     * @param string $acl
     */
    public function setAcl(Acl $acl)
    {
        $this->_acl = $acl;
    }
    
    /**
     * get Ressource
     * 
     * @return string
     */
    public function getRessource()
    {
        if(null === $this->_ressource) {
            $this->initRessource();
        }
        
        return $this->_ressource;
    }

    /**
     * set Ressource
     * 
     * @param string $ressource
     */
    public function setRessource($ressource)
    {
        $this->_ressource = $ressource;
    }
    
    /**
     * set Identity
     * 
     * @param type $identity
     * @return \Permission\Controller\Plugin\Authorize
     */
    public function setIdentity($identity) 
    {
        $this->_identity = $identity;
        return $this;
    }
    
    /**
     * get Identity if signed in
     * 
     * @return null|Identity
     */
    public function getIdentity() 
    {
        if(null === $this->_identity) {
            $this->initIdentity();
        }
            
        return $this->_identity;
    }
    
    /**
     * if signed in return true
     * 
     * @return bool
     */
    public function hasIdentity() 
    {
        return isset($this->_identity);
    }
 
  
    
    /**
     * Proves authorization. If ressource is not restricted, do nothing.
     * Otherwise proves authentication and permission.  
     * 
     * @param \Zend\Mvc\MvcEvent $event
     * @return Response
     */
    public function doAuthorization(MvcEvent $event)
    {
        $this->setEvent($event);
             
        $role       = $this->getRole();
        $acl        = $this->getAcl(); 
        $ressouce   = $this->getRessource();     
        
      
        //unknown ressource is free to everyone
        if( ! $acl->hasResource($ressouce)) 
            return $event->getResponse();
       
        if ( ! $this->hasIdentity()) {
            return $this->redirectToRoute('login');
        }
        
        if ( ! $acl->isAllowed($role, $ressouce)) {
            return $this->redirectToRoute('forbidden');
        }
        
        return $event->getResponse();
    }
    
     /**
     * set user's role from identity if signed in, otherwise
     * default role is set by default.
     * 
     * @return string
     */
    private function initRole()
    {
        $default    = $this->getDefaultRole();
        $identity   = $this->getIdentity();
        
        if( ! $this->hasIdentity()) {   
            return $this->setRole($default);
            
        }    
        
        $method = 'getRole';
        if(method_exists($identity, $method)) {
             
             $temp = $identity->$method(); 
             $role = empty($temp) ? $default : $temp; 
             return $this->setRole($role); 
        }
        
        $this->setRole($default); 
        
    }
    
    /**
     * set the identity using the authentication service.
     * Requires a logged in user. 
     */
    //initRessource //get+setRessource
    private function initIdentity() 
    {
        $event = $this->getEvent();
        
            //set authentication service
        $manager = $event->getApplication()->getServiceManager();
        
        if($manager->has('Zend\Authentication\AuthenticationService')) {
            $authService = 
                $manager->get('Zend\Authentication\AuthenticationService');
           
            if ($authService->hasIdentity()) {
                $identity = $authService->getIdentity();
                $this->setIdentity($identity);
            }    
        }
    }
    
     /**
     * set hardcoded ACL, its roles and ressources.
     * returns fluent interface.
     * edit this method for your need
     *  
     * @return \Zend\Permissions\Acl\Acl
     */
    private function initAcl()
    {
        //setting ACL...
        $acl = new Acl();
        
        $defaultRole = $this->getDefaultRole();
        //add roles ..
        $acl->addRole(new Role($defaultRole)); 
        $acl->addRole(new Role('guest'),  $defaultRole);
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
        
        
        $this->setAcl($acl);
     }
    
    /**
     * set the actual ressource from the request
     * 
     */
    private function initRessource()
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
        //$requestedRessouce = $controller . "\\" . $action; 
        
        $this->setRessource($controller);
        
    }
    
    /**
     * set the redirection and stops the propagation
     * 
     * @param string $route
     */
    private function redirectToRoute($route)
    {
        $event  = $this->getEvent();
        $router = $event->getRouter();
        $url    = $router->assemble(array(), array('name' => $route));
         
        $response = $event->getResponse();
        $response->setStatusCode(302);
        
        //redirect to login route...
        /* change with header('location: '.$url); if code below not working */
        $response->getHeaders()->addHeaderLine('Location', $url);
        $event->stopPropagation();
     
        return $response;
        
    }
    
}

?>
