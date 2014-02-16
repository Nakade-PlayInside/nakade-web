<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
/**
 * Description of PlayerMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PlayerMapper extends AbstractMapper 
{
    
    public function getAllPlayersInLeague($leagueId)
    {        
         return $this->getEntityManager()
                     ->getRepository('League\Entity\Participants')
                     ->findBy(array('_lid' => $leagueId)); 
    } 
    
    
    /**
    * Getting the number of players in a season
    * 
    * @param int $seasonId
    * @return int
    */
    public function getPlayerNumberInSeason($seasonId)  
    {
       $dql = "SELECT count(p) as number FROM 
               League\Entity\Participants p
               WHERE p._sid = :sid";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getSingleScalarResult();
       
    }
    
    /**
    * Get free players for a season. A free player is not 
    * already participating in that season.
    * 
    * @param int $seasonId
    * @return array
    */
    public function getFreePlayersForSeason($seasonId)  
    {
      
       $dql = "SELECT u FROM
               User\Entity\User u
               WHERE u.id NOT IN (SELECT p._uid FROM
               League\Entity\Participants p
               WHERE p._sid = :sid)";
       
       $players = $this->getEntityManager()
                       ->createQuery($dql)
                       ->setParameter('sid', $seasonId)        
                       ->getResult();
       
       return $players;
       
    }
    
     /**
    * Get all players in a season. 
    * 
    * @param int $seasonId
    * @return array
    */
    public function getPlayersInSeason($seasonId)  
    {
      
         return $this->getEntityManager()
                     ->getRepository('League\Entity\Participants')
                     ->findBy(array('_sid' => $seasonId)); 
       
    }
}

?>
