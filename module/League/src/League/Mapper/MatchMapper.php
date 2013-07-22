<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;


/**
 * Description of MatchMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MatchMapper  extends AbstractMapper 
{
    
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
    public function getMyOpenResults($seasonId, $uid)
    {
        
       $dql = "SELECT m FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id AND 
               (m._blackId = :uid OR m._whiteId = :uid) AND 
               m._resultId IS NULL 
               AND m._date < :today 
               ORDER BY m._date ASC";
       
       $today = new \DateTime();
       $today->modify('-6 hours');
          
       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('today', $today)
                   ->setParameter('uid', $uid)
                   ->setParameter('sid', $seasonId)        
                   ->getResult();
    }
    
     public function getMyResults($seasonId, $uid)
    {
        
       $dql = "SELECT m FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id AND 
               (m._blackId = :uid OR m._whiteId = :uid) 
               ORDER BY m._date ASC";
                 
       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('uid', $uid)
                   ->setParameter('sid', $seasonId)        
                   ->getResult();
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
     * get number of open matches in a season 
     * 
     * @param int $seasonId
     * @return int
     */
    public function getOpenMatches($seasonId)
    {
       
        $dql = "SELECT count(m) as number FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id AND 
               m._resultId IS NULL";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getSingleScalarResult();
       
    }
    
    /**
     * get number of matches in a season 
     * 
     * @param int $seasonId
     * @return int
     */
    public function getNumberOfMatches($seasonId)
    {
       
        $dql = "SELECT count(m) as number FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getSingleScalarResult();
       
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
    
    public function getLastMatchDate($seasonId)
    {
       
        $dql = "SELECT max(m._date) as datum FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getSingleScalarResult();
       
    }
    
    public function getFirstMatchDate($seasonId)
    {
       
        $dql = "SELECT min(m._date) as datum FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->getSingleScalarResult();
       
    }
    
    public function getLeagueInSeasonByPlayer($seasonId, $userId)
    {
        
        $dql = "SELECT l FROM 
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               m._lid=l._id AND
               m._blackId = :uid OR
               m._whiteId = :uid";
       
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)        
                    ->setParameter('uid', $userId)        
                    ->setMaxResults(1)
                    ->getOneOrNullResult();
        
    }
}

?>
