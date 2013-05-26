<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSesaonServiceFactory is needed to be set.
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
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.  
    */
    public function indexAction()
    {
        $lid=$this->getService()->getTopLeagueId();
            
        return new ViewModel(
           array(
              'title'     => $this->getService()->getTableShortTitle($lid),
              'table'     => $this->getService()
                                  ->getLeagueTable($lid)
           
           )
        );
    }
    
    /**
    * Shows actual matchplan and results of the league. 
    */
    public function scheduleAction()
    {
       //get param or top leagueId in actual season
       $lid  = $this->params()->fromRoute(
                  'lid', 
                  $this->getService()->getTopLeagueId()
               );
       
       return new ViewModel(
           array(
              'title'   => $this->getService()->getScheduleTitle($lid),
              'matches' => $this->getService()->getSchedule($lid),
           )
       );
    }
    
    /**
    * Shows detailed version of the league table. The Table is sortable.
    * Expected params for sorting and LeagueId. If no League Id is provided,
    * the Top League of the actual season is shown. 
    */
    public function tableAction()
    {
      
       //get param or top leagueId in actual season
       $lid  = $this->params()->fromRoute(
                  'lid', 
                  $this->getService()->getTopLeagueId()
               );
       
       //sorting the table
       $sorting  = $this->params()->fromRoute('sort', null);
      
       
       return new ViewModel(
           array(
              'table'       => $this->getService()
                                    ->getLeagueTable($lid, $sorting),
              'tiebreakers' => $this->getService()
                                    ->getTiebreakerNames($lid),
              'title'       => $this->getService()
                                    ->getTableTitle($lid),
              
           )
       );
    }
    
   
    
}
