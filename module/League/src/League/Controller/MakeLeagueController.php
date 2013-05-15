<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use League\Form\LeagueForm;
use League\Entity\League;
use League\Entity\Test;

/**
 * Season show controller. This is for showing stats only. 
 */
class MakeLeagueController extends AbstractTranslatorController
{
   /**
    * shows stats of the actual season if there is one. If not stats of the 
    * last or a new season is shown. If there is nothing to show you will
    * be informed by a text view.
    */
    public function indexAction()
    {
       
       $newSeason  = $this->season()->getNewSeason(); 
       if($newSeason==null)
           ; //add new season
           //
           
       //has Leagues()? 
          // add league
       
       
       //hasSeasons() ->
       //isActive() -> 
       //isClosed()
       //hasTwoOrMorePlayersInLeagues()
       //
       //
       //actual
       $actualSeason  = $this->season()->getActualSeason();
       if(!isset($actualSeason)) {
       
           return $this->forward()->dispatch(
               'league/controller/season', 
                array('action' => 'actual')
           );
       }
       
       //last
       $lastSeason  = $this->season()->getLastSeason();
       if(isset($lastSeason)) {
            
           return $this->forward()->dispatch(
               'league/controller/season', 
                array('action' => 'last')
           );
        }
        
       //new
       
       if(isset($newSeason)) {
           
           return $this->forward()->dispatch(
               'league/controller/season', 
                array('action' => 'new')
           );
       }    
       
       return new ViewModel(
           array(
              'schedule'  => false,
              'firstMatch'=> null,
              'lastMatch' => null, 
           )
       );
    }
    
    
   
    
    /**
     * show form to add a league to a new season  
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function newAction()
    {
       
       $season  = $this->season()->getNewSeason(); 
       $number   = $this->league()->getNoLeaguesInSeason($season) + 1;
       $league  = $this->league()->getLastLeagueByOrder($number);
       
       $title = $number . ". " . $this->translate("League");
       if($league != null && $league->getTitle()!=null)
           $title = $league->getTitle();
       
       //new season number 
       $this->getForm()
            ->setNumber($number)
            ->setTitle($title)
            ->setSeasonId($season->getId())
            ->init();
       
       return new ViewModel(
           array(
               'number'   => $season->getNumber(),
               'form'     => $this->getForm(),
               'messages' => $this->flashmessenger()->getMessages()
           )
       );
    }
    
    
   
    /**
    * shows actual matchplan and results of the top league 
    */
    public function editAction()
    {
       $season  = $this->season()->getActualSeason();
       
       if(is_null($season))
               return $this->redirect ()->toRoute ('season/showme');
       
       return new ViewModel(
           array(
              'number'    => $this->season()->getSeasonTitle($season),
              'noLeagues' => $this->league()->getNoLeaguesInSeason($season), 
              'noPlayers' => $this->player()->getNoPlayersInSeason($season), 
              'openGames' => $this->match()->getNoOpenGamesInSeason($season),
              'lastGame'  => $this->match()->getLastGameInSeason($season),
           )
       );
    }
    
    /**
     * add season to database 
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
       
       $request = $this->getRequest();
       $form    = $this->getForm();
      
      
       //proving if method is post
       if ($request->isPost()){
            
           $form->setData($request->getPost());
        
           //proving valid data
           if ($form->isValid()){
            
               $title  = $request->getPost('title');
               $sid    = $request->getPost('sid');
               $number  = $request->getPost('number');
            
               $league = new League();
               $league->setSid($sid);
               $league->setNumber($number);
               $league->setTitle($title);
              
               $this->league()->save($league);
               $this->redirect ()->toRoute ('newseason');
           
           }
           else {  

               $this->flashmessenger()->addMessage('validation failed');
           }  
        }
       
        return $this->forward()->dispatch(
               'league/controller/makeleague', 
                array('action' => 'new')
               );
         
       
    }
    
    /**
     * get the season form 
     * 
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        if(!isset($this->_form)) {
            $this->_form = new LeagueForm();
        }    
        
        return $this->_form;
    }
}