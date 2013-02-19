<?php
namespace League\Controller;

use League\Controller\LeagueController;
use Zend\Http\Request;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class LeagueControllerTest extends PHPUnit_Framework_TestCase
{
    protected $_controller;
    protected $_request;
    protected $_response;
    protected $_routeMatch;
    protected $_event;

    protected function setUp()
    {
        $bootstrap        = \Zend\Mvc\Application::init(
            include 'config/application.config.php'
        );
        
        
        $this->_controller = new LeagueController();
        $this->_request    = new Request();
        $this->_routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->_event      = $bootstrap->getMvcEvent();
        
        $this->_event->setRouteMatch($this->_routeMatch);
        $this->_controller->setEvent($this->_event);
        $this->_controller->setEventManager($bootstrap->getEventManager());
        $this->_controller->setServiceLocator($bootstrap->getServiceManager());
    }
    
    public function testAddActionCanBeAccessed()
    {
        $this->_routeMatch->setParam('action', 'add');

        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    public function testDeleteActionCanBeAccessed()
    {
        $this->_routeMatch->setParam('action', 'delete');

        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }

    public function testEditActionCanBeAccessed()
    {
        $this->_routeMatch->setParam('action', 'edit');
        
        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }
    
    public function testIndexActionCanBeAccessed()
    {
        $this->_routeMatch->setParam('action', 'index');

        $result   = $this->_controller->dispatch($this->_request);
        $response = $this->_controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
    }
}