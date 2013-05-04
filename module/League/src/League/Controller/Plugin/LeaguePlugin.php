<?php
namespace League\Controller\Plugin;

use League\Entity\Season;

class LeaguePlugin extends AbstractEntityPlugin
{
   
   /**
     * Getting the Top-League of the season
     * 
     * @param League/Entity/Season $season
     * @return /League/Entity/League $league
     */
    public function getTopLeague(Season $season)  
    {
       return $this->getLeague( $season, 1); 
    }
    
   /**
     * Getting the LeagueId 
     * 
     * @param League/Entity/Season $season
     * @param int league order number
     * @return /League/Entity/League $league
     */
    public function getLeague(Season $season, $order)  
    {
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\League'
       );
        
       $league = $repository->findOneBy(
           array('_sid'   => $season->getId() ,
                 '_order' => $order ,    
           )
       );
       
       return $league; 
    }
    
    /**
     * get number of leagues in season
     * 
     * @param League/Entity/Season $season
     * @return int 
     */
    public function getNoLeaguesInSeason(Season $season) 
    {
        $repository = $this->getEntityManager()->getRepository(
           'League\Entity\League'
       );
        
       $league = $repository->findBy(
           array('_sid'   => $season->getId() ,
           )
       );
       
       return count($league); 
    }
    
    
}

?>
