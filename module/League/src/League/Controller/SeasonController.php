<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;

/**
 * Season show controller. This is for showing stats only. 
 */
class SeasonController extends AbstractTranslatorController
{
   /**
    * shows stats of the actual season if there is one. If not stats of the 
    * last or a new season is shown. If there is nothing to show you will
    * be informed by a text view.
    */
    public function indexAction()
    {
       //actual
       $actualSeason  = $this->season()->getActualSeason();
       
       if(isset($actualSeason)) {
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
       $newSeason  = $this->season()->getNewSeason(); 
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
    * shows stats of the actual season 
    */
    public function actualAction()
    {
        
       $season  = $this->season()->getActualSeason();
      
       if(is_null($season)) {
          
          return $this->forward()->dispatch(
               'league/controller/season', 
                array(
                   'action' => 'noseason', 
                   'title'  => $this->translate('No actual season found.')
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
    * shows no season found
    */
    public function noseasonAction()
    {
      
       $title = $this->translate('No season found.'); 
       $param = $this->params()->fromRoute('title');
       
       if(isset($param))
           $title = $param;
           
       return new ViewModel(array('title' => $title));
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
                   'action' => 'noseason', 
                   'title'  => $this->translate('No last season found.')
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
    * shows infos about the new season 
    */
    public function newAction()
    {
      
       $season  = $this->season()->getNewSeason();
       
       if(is_null($season)) {
           
           return $this->forward()->dispatch(
               'league/controller/season', 
                array('action' => 'noseason')
           );
       }
      
       $noLeagues = $this->league()->getNoLeaguesInSeason($season);
       $noPlayers = $this->player()->getNoPlayersInSeason($season);
       $noGames   = $this->match()->getNoMatchesInSeason($season);
       $state     = $this->season()->getState($noLeagues,$noPlayers,$noGames);
       
       
       return new ViewModel(
           array(
              'number'    => $this->season()->getSeasonTitle($season),
              'noLeagues' => $noLeagues, 
              'noPlayers' => $noPlayers, 
              'noGames'   => $noGames,
              'start'     => $season->getYear()->format('d.m.Y'),
              'state'     => $state 
           )
       );
    }
    
    
   
}