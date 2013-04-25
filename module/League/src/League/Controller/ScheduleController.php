<?php
namespace League\Controller;

use Nakade\Controller\AbstractEntityManagerController;
use Zend\View\Model\ViewModel;


/**
 * Match Schedule Controller. Shows actual matchplan of the leagues.
 * By default the matchplan of the top league is shown 
 * 
 * @author Holger Maerz <holger@nakade.de>
 */
class ScheduleController extends AbstractEntityManagerController
{
    
    /**
    * shows actual matchplan and results of the top league 
    */
    public function indexAction()
    {
        
       $saison = $this->getActualSeason();
       $leagueId = $this->getLeague(
           $saison->getId(),
           1    
           ); 
       
       return new ViewModel(
           array(
              'year' => date_format($saison->getYear(), 'Y'),
              'pairings' => $this->getPairings($leagueId),
           )
       );
    }

    /**
     * helper for receiving the LeagueId 
     * 
     * @param int $seasonId
     * @param int $order
     * @return /League/Entity/Season $league
     */
    protected function getLeague($seasonId, $order) 
    {
        $repository = $this->getEntityManager()->getRepository(
           'League\Entity\League'
       );
        
       $league = $repository->findOneBy(
           array('_sid'   => $seasonId ,
                 '_order' => $order ,    
           )
       );
       
       return $league; 
    }
    

    /**
     * helper for getting the actual season
     * 
     * @return /League/Entity/Season $season
     */
    protected function getActualSeason() 
    {
        $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Season'
       );
        
       $season = $repository->findOneBy(
           array('_active' => 1), 
           array('_year'=> 'DESC')
           );
       
       return $season; 
    }
    
    /**
     * returns an array of matches
     * 
     * @param int $leagueId
     * @return array $pairings
     */
    protected function getPairings($leagueId)
    {
       
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Pairing'
       );
   
       $pairings = $repository->findby(
           array('_lid' => $leagueId), 
           array('_date'=> 'ASC')
           );
       
       return $pairings;
       
    }
}