<?php
namespace League\Mapper;

use Doctrine\ORM\EntityManager;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MatchMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MatchMapper  extends AbstractMapper 
{
    
    protected $_entity_manager;
    
    public function __construct($em) 
    {
        $this->_entity_manager=$em;
    }
    
    /**
     * Get all matches in league
     * 
     * @param int $leagueId
     * @return /League/Entity/Match $match
     */
    public function getMatchesInLeague($leagueId) 
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Match')
                   ->findBy(
                        array('_lid' => $leagueId), 
                        array('_date'=> 'ASC')
                     );
    }
    
     /**
     * Get all open results of the season.
     * It may happen to be more than one league only 
     * 
     * @param int $seasonId
     * @return array objects Match
     */
    public function getAllOpenResults($seasonId)
    {
        
       $dql = "SELECT m FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id AND 
               m._resultId IS NULL 
               AND m._date < :today 
               ORDER BY m._date ASC";
       
       $today = new \DateTime();
       $today->modify('-6 hours');
          
       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('today', $today)
                   ->setParameter('sid', $seasonId)        
                   ->getResult();
    }
    
    /**
     * get the match by id
     * 
     * @param int $id
     * @return League\Entity\Match
     */
    public function getMatchById($id)
    {
       
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Match')
                   ->find($id);
               
    }  
    
    /**
     * get all matches with a result in a league
     * 
     * @param int $leagueId
     * @return array League\Entity\Match
     */
    public function getAllMatchesWithResult($leagueId)
    {
       
       $dql = "SELECT m FROM 
               League\Entity\Match m
               WHERE m._lid = :lid AND
               m._resultId IS NOT NULL";
       
       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('lid', $leagueId)
                   ->getResult();
               
    }  
    
  
}

?>