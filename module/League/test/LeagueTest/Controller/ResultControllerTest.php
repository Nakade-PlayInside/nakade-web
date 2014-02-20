<?php
namespace League\Controller;


use LeagueTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use League\Controller\ResultController;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\Parameters;
use PHPUnit_Framework_TestCase;

class ResultControllerTest extends PHPUnit_Framework_TestCase
{
    protected $_controller;
    protected $_request;
    protected $_response;
    protected $_routeMatch;
    protected $_event;

    protected function setUp()
    {
        $serviceManager    = Bootstrap::getServiceManager();
        $this->_controller = new ResultController();
        
        
        $this->_request    = new Request();
        $this->_routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->_event      = new MvcEvent();
        
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        
        $this->_event->setRouter($router);
        $this->_event->setRouteMatch($this->_routeMatch);
        $this->_controller->setEvent($this->_event);
        $this->_controller->setServiceLocator($serviceManager);
       
    }
  
    public function testInitialState()
    {
        $this->assertNull(
                $this->_controller->getService(),
                 sprintf('"getService()" should initially be null')
        );
        
        
    }
    
    public function testSetsPropertiesCorrectly()
    {
        $this->_controller->setService(array('myObject'));
        
        $this->assertSame(
            array('myObject'), 
            $this->_controller->getService(), 
            sprintf('"service" was not set correctly')
        );
        
        
    }
    
    public function testIndexActionCanBeAccessed()
    {
        
        $this->_routeMatch->setParam('action', 'index');

        //setup a mock service with three methods
        $mock = $this->getMock(
                'service',
                array('getOpenResultTitle','getOpenResult')
                );
               
        $this->_controller->setService($mock);
        
        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }
    
    public function testAddresultActionCanBeAccessed()
    {
        
        $this->_routeMatch->setParam('action', 'addresult');
        
        $mock = $this->getMock(
                'service',
                array('setResultFormValues', 'getMatch')
                );
               
        $this->_controller->setService($mock);
        
        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
       
    }
    
     public function testAddresultIsPost()
    {
      
        $mock = $this->getMock(
                'service',
                array(
                    'setResultFormValues', 
                    'getMatch', 
                    'prepareFormForValidation',
                    'processResultData'
                )
        );
               
        $this->_controller->setService($mock);
        
        $this->_routeMatch->setParam('action', 'addresult');
        $this->_request->setMethod('POST');
        $this->_request->setPost(new Parameters(array('input' => 'some different')));
        
       $result   = $this->_controller->dispatch($this->_request);
       $response = $this->_controller->getResponse();
        
       $this->assertEquals(200, $response->getStatusCode());
        
    }
}