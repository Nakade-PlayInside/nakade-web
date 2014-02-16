<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
/**
 * Description of LeagueMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class LeagueMapper extends AbstractMapper 
{
    

   /**
     * Getting the LeagueId 
     * 
     * @param int $seasonId
     * @param int $number league number
     * @return /League/Entity/League $league
     */
    public function getLeague($seasonId, $number)  
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\League')
                   ->findOneBy(
                        array(
                           '_sid'   => $seasonId,
                           '_number' => $number,
                        )
                     );
    }
    
    /**
     * Getting the League by Id
     * 
     * @param int $leagueId
     * @return /League/Entity/League $league
     */
    public function getLeagueById($leagueId)  
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\League')
                   ->find($leagueId);
    }
    
    /**
    * Getting the number of league in a season
    * 
    * @param int $seasonId
    * @return int
    */
    public function getLeaguesWithPlayers($seasonId)  
    {
        
       $dql = "SELECT count(l) as number FROM 
               League\Entity\League l,
               League\Entity\Participants p
               WHERE l._sid = :sid AND
               p._lid = l._id";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getSingleScalarResult();
       
    }
    
    /**
    * Getting the number of league in a season
    * 
    * @param int $seasonId
    * @return int
    */
    public function getLeagueNumberInSeason($seasonId)  
    {
       $dql = "SELECT count(l) as number FROM 
               League\Entity\League l
               WHERE l._sid = :sid";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getSingleScalarResult();
       
    }
    
    /**
    * Get all leagues of a season
    * 
    * @param int $seasonId
    * @return int
    */
    public function getLeaguesInSeason($seasonId)  
    {
       $dql = "SELECT l FROM 
               League\Entity\League l
               WHERE l._sid = :sid";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getResult();
       
    }
}
 
?>
