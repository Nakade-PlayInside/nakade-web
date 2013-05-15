<?php
namespace League\Controller;


use LeagueTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use League\Controller\ActualSeasonController;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class ActualSeasonControllerTest extends PHPUnit_Framework_TestCase
{
    protected $_controller;
    protected $_request;
    protected $_response;
    protected $_routeMatch;
    protected $_event;

    protected function setUp()
    {
        $serviceManager    = Bootstrap::getServiceManager();
        $this->_controller = new ActualSeasonController();
        
        
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
  
    public function testIndexActionCanBeAccessed()
    {
        $this->_routeMatch->setParam('action', 'index');

        
        $mock = $this->getMock(
                'service',
                array('getTableShortTitle','getTable')
                );
               
        $this->_controller->setService($mock);
        
        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }
    
    public function testScheduleActionCanBeAccessed()
    {
        $this->_routeMatch->setParam('action', 'schedule');

        
        $mock = $this->getMock(
                'service',
                array('getScheduleTitle','getSchedule')
                );
               
        $this->_controller->setService($mock);
        
        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }
    
     public function testTableActionCanBeAccessed()
    {
        $this->_routeMatch->setParam('action', 'table');

        
        $mock = $this->getMock(
                'service',
                array('getTableTitle','getTable')
                );
               
        $this->_controller->setService($mock);
        
        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }
}