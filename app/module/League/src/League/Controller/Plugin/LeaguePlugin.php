<?php
namespace League\Controller\Plugin;

use League\Entity\Season;

/**
 * PlugIn for managing league database requests.
 */
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
     * @param int order of the league
     * @return /League/Entity/League $league
     */
    public function getLeague(Season $season, $number)  
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\League')
                   ->findOneBy(
                        array(
                           '_sid'   => $season->getId(),
                           '_number' => $number,
                        )
                     );
    }
    
    /**
     * get all leagues in a season
     * 
     * @param League/Entity/Season $season
     * @return array of entities 
     */
    public function getAllLeaguesInSeason(Season $season) 
    {
        return $this->getEntityManager()
                    ->getRepository('League\Entity\League')
                    ->findBy(
                         array('_sid' => $season->getId()),
                         array('_number' => 'ASC')   
                      );
    }
    
    /**
     * get number of leagues in season
     * 
     * @param League/Entity/Season $season
     * @return int 
     */
    public function getNoLeaguesInSeason(Season $season) 
    {
        $league = $this->getAllLeaguesInSeason($season);
        return count($league); 
    }
 
    
    public function getLastLeagueByOrder($number) 
    {
        
     
      $dql = "SELECT l as last FROM League\Entity\League l
              WHERE l._number=:number
              ORDER BY l._id DESC";
      
      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('number', $number)
                     ->setMaxResults(1)
                     ->getOneOrNullResult();
       
      return $result['last'];
       
    }
    
    public function getFormLeagues(Season $season)
    {        
        $leagues  = $this->getAllLeaguesInSeason($season);
       
        $league =array();
        foreach($leagues as $res) {
            
            //text
            $title = $res->getTitle();
            if($res->getTitle()==null)
                $title = $this->getController()
                              ->translate('League No. ') . $res->getNumber();
            
            $league[$res->getId()] = $title; 
        }
        return $league;
    }
}

?>
