<?php
namespace League\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSeasonServiceFactory is needed to be set.
 *   
 * @author Holger Maerz <holger@nakade.de>
 */
class ActualSeasonController extends AbstractController
{
    
    /**
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.  
    */
    public function indexAction()
    {
                 
        return new ViewModel(
           array(
              'title'     => $this->getService()->getTitle(),
              'table'     => $this->getService()->getTopLeagueTable()
           
           )
        );
    }
    
    /**
    * Shows actual matchplan of a user and his results.
    * If user is not in  a league, the top league schedule
    * is shown.
    *  
    */
    public function scheduleAction()
    {
       $user = $this->identity();
        
       if($user === null) {
           return $this->redirect()->toRoute('login');
       }
       $uid = $user->getId();
             
       return new ViewModel(
           array(
              'title'   => $this->getService()->getScheduleTitle($uid),
              'matches' => $this->getService()->getSchedule($uid),
           )
       );
    }
    
    /**
    * Shows the user's league table. If user is not in a league, the
    * Top league is shown instead. The Table is sortable.
    */
    public function tableAction()
    {
     
       $user = $this->identity();
        
       if($user === null) {
           return $this->redirect()->toRoute('login');
       }
       $uid = $user->getId();
        
       
       //sorting the table
       $sorting  = $this->params()->fromRoute('sort', null);
      
       
       return new ViewModel(
           array(
              'table'       => $this->getService()
                                    ->getLeagueTable($uid, $sorting),
              'tiebreakers' => $this->getService()
                                    ->getTiebreakerNames(),
              'title'       => $this->getService()
                                    ->getTableTitle($uid),
              
           )
       );
    }
    
   
    
}
