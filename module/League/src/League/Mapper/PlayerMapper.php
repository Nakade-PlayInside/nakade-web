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
    
}

?>
