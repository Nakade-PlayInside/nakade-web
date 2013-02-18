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
    
    protected function getTopTable()
    {
       
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Position'
       );
       
       $position = $repository->findBy(
           array('_lid' => 1), 
           array(
              '_win'=> 'DESC', 
              '_tiebreaker1'=> 'DESC',
              '_tiebreaker2'=> 'DESC',
              '_gamesPlayed'=> 'DESC',
              '_id'=> 'DESC'
              )
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
