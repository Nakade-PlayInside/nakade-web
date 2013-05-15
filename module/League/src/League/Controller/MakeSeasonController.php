<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use League\Form\SeasonForm;
use League\Entity\Season;

/**
 * Season show controller. This is for showing stats only. 
 */
class MakeSeasonController extends AbstractTranslatorController
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
     * make first season  
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function newAction()
    {
       
       //new season number 
       $defaults  = $this->season()->getNewSeasonDefaults();
     
       $number = $defaults['number'];
       $title  = $defaults['title'];
       $date   = $defaults['year'];
       
       
       $this->getForm()
            ->setNumber($number)
            ->setTitle($title)
            ->setDate($date)
            ->init();
       
       return new ViewModel(
           array(
               'number'   => $number,
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
     * make first season  
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function newSeasonAction()
    {
       
       //if null -> FIRST SEASON 
       $season  = $this->season()->getLastSeason();
       
       //hasSeasons() ->
       //isActive() -> 
       //isClosed()
       //hasTwoOrMorePlayersInLeagues()
       
       
       $newSeason = new \League\Entity\Season();
       
        //var_dump($season);
       if(is_null($season)) {
            return $this->redirect ()->toRoute ('season/showme');
       }        
       //close season if all games played
       //but still active
       
       $my = $season->getArrayCopy();
       
       $my = new \League\Entity\Season();
       $my->setNumber($season->getNumber()+1);
      // $my->setActive(0);
     //  $this->season()->save($my);
       
       $form1 = new SeasonForm($season->getTitle());
     //  $form->init();
       
       return new ViewModel(
           array(
               'number'  => $season->getNumber()+1,
               'form'    => $form1,
               'season'  => $my->getArrayCopy()
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
               $year   = $request->getPost('year');
               $number = $request->getPost('number');
            
               $new = new Season();
               $new->setNumber($number)
                   ->setTitle($title)
                   ->setActive(false)
                   ->setClosed(false)     
                   ->setYear(new \DateTime($year));
               
               $this->season()->save($new);
               $this->redirect ()->toRoute ('newseason');
           
           }
           else {  

               $this->flashmessenger()->addMessage('validation failed');
           }  
        }
       
        return $this->forward()->dispatch(
               'league/controller/makeseason', 
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
            $this->_form = new SeasonForm();
        }    
        
        return $this->_form;
    }
}