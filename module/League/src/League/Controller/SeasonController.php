<?php
namespace League\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use League\Form\SeasonForm;
use Zend\I18n\Translator\Translator;

/**
 * Match Schedule Controller. Shows actual matchplan of the leagues.
 * By default the matchplan of the top league is shown 
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class SeasonController extends AbstractActionController
{
    protected $_translator;
    
    /**
    * shows actual matchplan and results of the top league 
    */
    public function indexAction()
    {
        
       $season  = $this->season()->getLastSeason();
       
       //hasSeasons() -> first Season
       if(is_null($season)) {
            return $this->redirect ()->toRoute ('season/first');
       }   
       
       //isActive() 
       if($season->isActive()) {
            return $this->redirect ()->toRoute ('season/active');
       }
     
       return new ViewModel(
           array(
              'number'    => $this->season()->getSeasonTitle($season),
              'title'     => $season->getTitle(),
              'seasonDate'=> date_format($season->getYear(), 'd.m.Y'),
              'noLeagues' => $this->league()->getNoLeaguesInSeason($season), 
              'noPlayers' => $this->player()->getNoPlayersInSeason($season), 
              'matches'   => 0,
              'schedule'  => false,
              'firstMatch'=> null,
              'lastMatch' => null, 
           )
       );
    }
    
    /**
    * shows stats of the actual season 
    */
    public function actualAction()
    {
        
       $season  = $this->season()->getActualSeason();
     
       if(is_null($season)) {
           
          // $info = $this->getTranslator()->translate(
          //                                  'No actual season found.',
          //                                  'League'
          //                                  );
           
           return $this->forward()->dispatch(
               'league/controller/season', 
                array(
                   'action' => 'init', 
                   'title'  => 'No actual season found.',
                )
           );
       }
      
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
    * shows initial state: no season found
    */
    public function initAction()
    {
      
       return new ViewModel(
            array('title' => $this->params()->fromRoute('title')));
    }
    
    /**
    * shows results of the last season 
    */
    public function lastAction()
    {
        
       $season  = $this->season()->getLastSeason();
       
       if(is_null($season)) {
           
           return $this->forward()->dispatch(
               'league/controller/season', 
                array(
                   'action' => 'init', 
                   'title'  => 'No last season found.',
                )
           );
       }
       
       
       return new ViewModel(
           array(
              'number'    => $this->season()->getSeasonTitle($season),
              'noLeagues' => $this->league()->getNoLeaguesInSeason($season), 
              'noPlayers' => $this->player()->getNoPlayersInSeason($season), 
              'openGames' => $this->match()->getNoMatchesInSeason($season),
              'winner'    => $this->table()->getChampion($season),
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
    public function newAction()
    {
       
       $season  = $this->season()->getLastSeason();
       
       //hasSeasons() -> first Season
       if(is_null($season)) {
            return $this->redirect ()->toRoute ('season/first');
       }   
       
       //isActive() 
       if($season->isActive()) {
            return $this->redirect ()->toRoute ('season/active');
       }
       
       
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
     * make first season  
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function gammelAction()
    {
       
       $request = $this->getRequest();
        
        //proving if method is post
        if ($request->isPost()){
            
            $title  = $request->getPost('title');
            $year   = $request->getPost('year');
            $number = $request->getPost('number');
            
            $my = new \League\Entity\Season();
            $my->setNumber($number);
            $my->setTitle($title);
            $my->setYear(new \DateTime($year));
           
           
            $this->season()->save($my);
        }
        
      //  $this->redirect ()->toRoute ('season');
    }
    
    /**
     * Setter for translator in form. Enables the usage of i18N.
     * 
     * @param \Zend\I18n\Translator\Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->_translator = $translator;
    }
    
    /**
    * getter 
    * 
    * @return \Zend\I18n\Translator\Translator $translator
    */
    public function getTranslator()
    {
        return $this->_translator;
    }
    
}