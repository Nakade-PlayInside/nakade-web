<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Match Schedule Controller. Shows actual matchplan of the leagues.
 * By default the matchplan of the top league is shown 
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class ScheduleController extends AbstractActionController
{
    
    /**
    * shows actual matchplan and results of the top league 
    */
    public function indexAction()
    {
       //plugin for database logic
       $season = $this->season()->getActualSeason();
       
       if(is_null($season))
               return $this->redirect ()->toRoute ('season/showme');
       
       $league = $this->league()->getTopLeague($season);
       
       return new ViewModel(
           array(
              'number'  => $this->season()->getSeasonTitle($season),
              'matches' => $this->match()->getMatchesInLeague($league),
           )
       );
    }

   
}