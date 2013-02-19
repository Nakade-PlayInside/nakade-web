<?php
namespace League\Controller;

use Nakade\Controller\AbstractEntityManagerController;
use Zend\View\Model\ViewModel;

/**
 * Manages a round-robin league. Registered players are connected to the 
 * User Module. The league contains league tables with tie-breakers and is part
 * of a season. The pairings have a given date to play and collecting the 
 * results. The ruleset decides which tie-breakers are used for the placement.
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class LeagueController extends AbstractEntityManagerController
{
    /**
    * viewhelper to exhibit the standings of the top league 
    */
    public function indexAction()
    {
        
       return new ViewModel(
           array(
              'users' => $this->getTopTable()
           )
       );
    }
    
    public function comingNextAction()
    {
        
       return new ViewModel(
           array(
              'users' => $this->getNextGame()
           )
       );
    }
    
    
    protected function getTopTable()
    {
       
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Position'
       );
       
       $position = $repository->findBy(
           array('_lid' => 1), 
           array(
              '_win'=> 'DESC', 
              '_tiebreakerA'=> 'DESC',
              '_tiebreakerB'=> 'DESC',
              '_gamesPlayed'=> 'DESC',
              '_id'=> 'DESC'
              )
       );
       
       return $position;
       
    }
    
    protected function getNextGame()
    {
       
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Pairing'
       );
       
       
       //@todo: datumsvergleich jetzt zu nächsten termin
       //@todo: nur aktuelle Termine, nicht die Spiele,
       //die noch nicht eingetragen sind 
       
       $position = $repository->findBy(
           array('_lid' => 1, '_resultId' => NULL,), 
           array(
              '_date'=> 'ASC', 
              ),
           1    
       );
       
       return $position;
       
    }
    
    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
    
    
}
