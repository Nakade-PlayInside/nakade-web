<?php
namespace League\Mapper;

use League\Entity\Season; 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SeasonMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class SeasonMapper  extends AbstractMapper 
{
   protected $_entity_manager;
    
    public function __construct($em) 
    {
        $this->_entity_manager=$em;
    }
    
   /**
   * Getting the Actual Season which is ongoing season where  
   * matches are still to be played.
   * Probably, the actual season is not the last season.
   * 
   * @return /League/Entity/Season $season
   */
   public function getActualSeason() 
   {
      
      $dql = "SELECT s as actual FROM League\Entity\Season s
              WHERE s._active=1";
      
      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->getOneOrNullResult();
      
      return $result['actual'];
     
   }
   
   /**
   * Getting the Season of a league.
   * 
   * @return /League/Entity/Season $season
   */
   public function getSeasonByLeagueId($lid) 
   {
      
      $dql = "SELECT s as season FROM League\Entity\Season s,
              League\Entity\League l
              WHERE l._id=:leagueId AND
              s._id=l._sid";
      
      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('leagueId', $lid)
                     ->getOneOrNullResult();
      
      return $result['season'];
     
   }
   
}

?>
