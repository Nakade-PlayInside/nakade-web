<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use League\Form\ParticipantsForm;
use League\Entity\Season;

/**
 * Season show controller. This is for showing stats only. 
 */
class MakePlayerController extends AbstractTranslatorController
{
   /**
    * shows stats of the actual season if there is one. If not stats of the 
    * last or a new season is shown. If there is nothing to show you will
    * be informed by a text view.
    */
    public function indexAction()
    {
       
       $allPlayers  = $this->player()->getAllPlayers(); 
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
       
       //new season
       $season  = $this->season()->getNewSeason();
       $leagues = $this->league()->getFormLeagues($season);
       $players = $this->player()->getFormPlayers($season); 
       
       $this->getForm()
            ->setPlayers($players)
            ->setLeagues($leagues)
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
            $this->_form = new ParticipantsForm();
        }    
        
        
        return $this->_form;
    }
}