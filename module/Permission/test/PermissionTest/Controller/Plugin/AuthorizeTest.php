<?php
namespace PermissionTest\Controller\Plugin;
  
use Permission\Controller\Plugin\Authorize;
use PermissionTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class AuthorizeTest extends PHPUnit_Framework_TestCase
{
    protected $_request;
    protected $_response;
    protected $_routeMatch;
    protected $_event;

    protected function setUp()
    {
        $serviceManager    = Bootstrap::getServiceManager();
        
        $this->_request    = new Request();
        $this->_routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->_event      = new MvcEvent();
        
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        
        $response   = new \Zend\Http\PhpEnvironment\Response();
        $response->setStatusCode(200);
        
       // $this->_event->setApplication($application);
        $this->_event->setResponse($response);
        $this->_event->setRouter($router);
        $this->_event->setRouteMatch($this->_routeMatch);
       
    }
    
    public function testInitialState()
    {
        $object = new Authorize();
            
        $this->assertNull(
            $object->getEvent(), 
            sprintf('"%s" should initially be null', 'getEvent()')
            
        );
        
        $this->assertFalse(
            $object->hasIdentity(), 
            sprintf('"%s" should initially be false', 'hasIdentity()')
            
        );
     
    }
    
    public function testSetsPropertiesCorrectly()
    {
        $object = new Authorize();
        $object->setEvent($this->_event);
        
        $this->assertSame(
            $object->getEvent(), 
            $this->_event,   
            sprintf('"%s" was not set correctly', 'event')
        );
        
        $mock = $this->getIdentityMock();
        $object->setIdentity($mock);
        
        $this->assertSame(
            $object->getIdentity(), 
            $mock,   
            sprintf('"%s" was not set correctly', 'identity')
            
        );
        
        $role = "User";
        $object->setRole($role);
        
        $this->assertSame(
            $object->getRole(), 
            $role,   
            sprintf('"%s" was not set correctly', 'role')
            
        );
        
        $ressource = "testMe";
        $object->setRessource($ressource);
        
        $this->assertSame(
            $object->getRessource(), 
            $ressource,   
            sprintf('"%s" was not set correctly', 'ressource')
            
        );
        
        $acl = $this->getAclMock();
        $object->setAcl($acl);
        
        $this->assertSame(
            $object->getAcl(), 
            $acl,   
            sprintf('"%s" was not set correctly', 'acl')
            
        );
        
        $this->assertTrue(
            $object->hasIdentity(), 
            sprintf('"%s" expected to return true', 'hasIdentity')
            
        );  
    }
   
    public function testFunctionalityGetRole()
    {
        
        $object = new Authorize();
        
        $role ='user';
        $identity = $this->getIdentityMock($role);
        $object->setIdentity($identity);
        
        $this->assertTrue($object->hasIdentity());
        $this->assertSame($object->getIdentity()->getRole(), $role);
        
        $this->assertSame(
            $object->getRole(), 
            $role,   
            sprintf('"%s" was not set correctly', 'role')
            
        );
        
    }
    
    public function testFunctionalityGetAcl()
    {
        
        $object = new Authorize();
        
        $this->assertInstanceOf(
                '\Zend\Permissions\Acl\Acl', 
                $object->getAcl(),
                sprintf('"%s" was not set correctly', 'acl')
        );
        
        
    }
    
    public function testFunctionalityGetRessource()
    {
        
        $object = new Authorize();
        $object->setEvent($this->_event);
        
        $this->assertInstanceOf(
                '\Zend\Mvc\Router\RouteMatch', 
                $this->_event->getRouteMatch()
        );
        
        $controller = 'index';
        $this->assertSame(
                $controller,
                $this->_event->getRouteMatch()->getParam('controller')
        );
        
        $this->assertSame(
                $controller,
                $object->getRessource(),
                sprintf('"%s" was not set correctly', 'ressource')
        );
        
    }
   
   
    public function testIsAllowed()
    {
        
        $object = new Authorize();
        
        $role ='user';
        $identity = $this->getIdentityMock($role);
        $object->setIdentity($identity);
        
        $acl = $this->getAclMock();
        $object->setAcl($acl);
        
        $response   = $object->doAuthorization($this->_event);
        
        //hasRes && isAllowed
        $this->assertTrue($object->hasIdentity());
        $this->assertTrue($object->getAcl()->hasResource('test'));
        $this->assertTrue($object->getAcl()->isAllowed());
        
        $this->assertEquals(200, $response->getStatusCode());
        
    }
  
    public function testHasNoRessource()
    {
        
        $object = new Authorize();
        
        $role ='user';
        $identity = $this->getIdentityMock($role);
        $object->setIdentity($identity);
        
        $acl = $this->getAclMock(false);
        $object->setAcl($acl);
       
        $response   = $object->doAuthorization($this->_event);
        $this->assertFalse($object->getAcl()->hasResource('test'));
        $this->assertEquals(200, $response->getStatusCode());
        
    }
   
    public function testHasNoIdentity()
    {
        
        $object = new Authorize();
        
        $acl = $this->getAclMock();
        $object->setAcl($acl);
        $object->setRole("User");
        
        $response   = $object->doAuthorization($this->_event);
        
        $this->assertFalse($object->hasIdentity());
        $this->assertEquals(302, $response->getStatusCode());
        
    }

    public function testIsNotAllowed()
    {
        
        $object = new Authorize();
        
        $role ='user';
        $identity = $this->getIdentityMock($role);
        $object->setIdentity($identity);
        
        $acl = $this->getAclMock(true, false);
        $object->setAcl($acl);
        
        $response   = $object->doAuthorization($this->_event);
        
        $this->assertTrue($object->getAcl()->hasResource('test'));
        $this->assertTrue($object->hasIdentity());
        $this->assertEquals(302, $response->getStatusCode());
        
    }

    
    
    private function getIdentityMock($value='admin')
    {
        
        $mock = $this->getMock(
                'Identity', 
                array('getRole')
        );
        
        $mock->expects($this->any())
             ->method('getRole')
             ->will($this->returnValue($value));
        
        
        return $mock;
    }
    
    private function getAclMock($hasRessource=true, $allow=true)
    {
        
        $mock = $this->getMock(
                'Zend\Permissions\Acl\Acl', 
                array('addRole', 'addRessouce', 'hasResource','isAllowed')
        );
        
        $mock->expects($this->any())
             ->method('hasResource')
             ->will($this->returnValue($hasRessource));
        
        $mock->expects($this->any())
             ->method('isAllowed')
             ->will($this->returnValue($allow));
        
        return $mock;
    }
    
   
}

?>
