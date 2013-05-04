<?php
namespace League\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Manages a round-robin league. Registered players are connected to the 
 * User Module. The league contains league tables with tie-breakers and is part
 * of a season. The pairings have a given date to play and collecting the 
 * results. The ruleset decides which tie-breakers are used for the placement.
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class TableController extends AbstractActionController
{
   /**
    * 
    */
    public function indexAction()
    {
       
       
       $season =  $this->season()->getActualSeason();
       $league       =  $this->league()->getTopLeague($season);
        
       return new ViewModel(
           array(
              'number'    => $this->season()->getSeasonShortTitle($season),
              'title'     => $season->getTitle(),
              'users' => $this->table()->getTable($league),
           )
       );
    }
    
    
    
}
