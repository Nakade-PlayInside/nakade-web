<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Manages a round-robin league. Registered players are connected to the 
 * User Module. The league contains league tables with tie-breakers and is part
 * of a season. The pairings have a given date to play and collecting the 
 * results. The ruleset decides which tie-breakers are used for the placement.
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class ActualSeasonController 
    extends AbstractActionController implements MyServiceInterface
{
    protected $_service;
    
    public function getService()
    {
       return $this->_service;    
    }
    
    public function setService($service)
    {
        $this->_service = $service;
        return $this;
    }       
    
     /**
    * viewhelper to exhibit the standings of the top league 
    */
    public function indexAction()
    {
    
        return new ViewModel(
           array(
              'title'     => $this->getService()->getTableShortTitle(),
              'table'     => $this->getService()->getTable(),
           
           )
        );
    }
    
    /**
    * shows actual matchplan and results of the top league 
    */
    public function scheduleAction()
    {
       
       return new ViewModel(
           array(
              'title'   => $this->getService()->getScheduleTitle(),
              'matches' => $this->getService()->getSchedule(),
           )
       );
    }
    
    /**
    * shows actual table of the top league 
    */
    public function tableAction()
    {
      
       return new ViewModel(
           array(
              'title'     => $this->getService()->getTableTitle(),
              'table'     => $this->getService()->getTable(),
           
           )
       );
    }
   
    /**
    * shows actual table of the top league 
    */
    public function calctableAction()
    {
      
       return new ViewModel(
           array(
              'table'     => $this->getService()->getCalculatedTable(),
              
           )
       );
    }
    
   
    
}
