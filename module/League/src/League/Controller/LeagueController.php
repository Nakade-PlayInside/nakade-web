<?php
namespace League\Controller;

use Zend\View\Model\ViewModel;
use League\Form\ResultForm;
use League\Helper\PositionCalculator;

/**
 * Manages a round-robin league. Registered players are connected to the 
 * User Module. The league contains league tables with tie-breakers and is part
 * of a season. The pairings have a given date to play and collecting the 
 * results. The ruleset decides which tie-breakers are used for the placement.
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class LeagueController extends AbstractTranslatorController
{
    protected $_season_mapper_service;
    
    public function getSeasonMapperService()
    {
       return $this->_season_mapper_service;    
    }
    
    public function setSeasonMapperService($service)
    {
        $this->_season_mapper_service = $service;
        return $this;
    }        
    /**
    * viewhelper to exhibit the standings of the top league 
    */
    public function indexAction()
    {
      
      
       //better to get the last season 
       $actualSeason =  $this->getSeasonMapperService()->getActualSeason();
       $league       =  $this->league()->getTopLeague($actualSeason);
        
       return new ViewModel(
           array(
              'users' => $this->table()->getTable($league),
              'nextGames' => $this->match()->getNextThreeGames($league),
           )
       );
    }
    
    public function comingNextAction()
    {
        
       return new ViewModel(
           array(
              'users' => $this->match()->getNextGame()
           )
       );
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function openResultsAction()
    {
       
       if ($this->identity()) {
        
           $actualSeason =  $this->season()->getActualSeason();
           $matches     =  $this->match()->getAllOpenResults($actualSeason);
           
           return new ViewModel(
               array('matches' => $matches)
           );
       } 
    
       else {
           return $this->redirect()->toRoute('login');
       }
    
       
    }

    
    public function addAction()
    {
        $pid = (int) $this->params()->fromRoute('id', 0);
        
        $game= $this->match()->getMatch($pid);
        $lid = $game->getLid();
        $blackId = $game->getBlackId();
        $whiteId = $game->getWhiteId();
        
        $black=$this->table()->getPlayerStatsInLeague($blackId,$lid);
        $white=$this->table()->getPlayerStatsInLeague($whiteId,$lid);
        $resultList=$this->result()->getResultlist();
        
      
        $form = new ResultForm($game, $resultList);
        $form->setBindOnValidate(false);
        $form->bind($game);
         
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                
                $form->bindValues();
              //  $this->table()->save();
              
                
                $calc = new PositionCalculator($request->getPost());     
                $calc->bindEntity($black);
                $calc->bindEntity($white);
               
                
                $this->table()->save($black);
                $this->table()->save($white);
              
                // Redirect to list of albums
                return $this->redirect()->toRoute('league');
            }
        }
        
          
        return array('id' => $pid, 'game' => $game, 'form' => $form);
    }

    
    
}
