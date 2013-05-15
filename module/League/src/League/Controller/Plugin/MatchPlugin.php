<?php
namespace League\Controller\Plugin;

use League\Entity\League;
use League\Entity\Season;
use League\Entity\Match;
/**
 * PlugIn for managing match database requests.
 */
class MatchPlugin extends AbstractEntityPlugin
{
    /**
     * Get all matches in league
     * 
     * @param /League/Entity/League $league object
     * @return /League/Entity/Match $match
     */
    public function getMatchesInLeague(League $league) 
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Match')
                   ->findBy(
                        array('_lid' => $league->getId()), 
                        array('_date'=> 'ASC')
                     );
    }
    
    
    /**
    * Get all matches in season
    * 
    * @param /League/Entity/Season $season object
    * @return array of /League/Entity/Match $match
    */
    public function getMatchesInSeason(Season $season) 
    {
       
      $dql="SELECT m FROM League\Entity\Match m,
            League\Entity\League l
            WHERE m._lid=l._id
            AND l._sid=:sid
            ORDER BY m._date ASC";
        
      return $this->getEntityManager()
                  ->createQuery($dql)
                  ->setParameter('sid', $season->getId())
                  ->getResult();
       
    }
    
    /**
    * Get number of all matches in season
    * 
    * @param /League/Entity/Season $season object
    * @return array of /League/Entity/Match $match
    */
    public function getNoMatchesInSeason(Season $season) 
    {
       return count($this->getMatchesInSeason($season)); 
    }
    
    /**
     * get number of open games in a season
     * 
     * @param Season $season
     * @return int $matches
     */
    public function getNoOpenGamesInSeason(Season $season)
    {
        $dql="SELECT m FROM League\Entity\Match m,
              League\Entity\League l
              WHERE m._lid=l._id
              AND l._sid=:sid
              AND m._resultId IS NULL 
              ORDER BY m._date ASC";
       
        $matches = $this->getEntityManager()
                        ->createQuery($dql)
                        ->setParameter('sid', $season->getId())
                        ->getResult();
       
       return count($matches);
       
    }
    
    /**
    * get the date of the last game in a season.
    * Format is MySQL datetime
    * 
    * 
    * @param Season $season
    * @return DateTime 
    */
    public function getLastGameInSeason(Season $season)
    {
       $dql = "SELECT MAX(u._date) as last FROM League\Entity\Match u,
               League\Entity\League l
               WHERE u._lid=l._id
               AND l._sid=:sid" ;
       
       $result = $this->getEntityManager()
                      ->createQuery($dql)
                      ->setParameter('sid', $season->getId())
                      ->setMaxResults(1)
                      ->getSingleResult();
               
       return $result['last'];
       
    }
    
    /**
     * Get the next three games in the league.
     * There is a delay of 6 hours for exposing
     * the matches to make sure that the actual 
     * played game is still shown online. 
     *  
     * @param League $league entity
     * @return array Match
     */
    public function getNextThreeGames(League $league)
    {
       $today = new \DateTime();
       $today->modify('+6 hour');
       
       $dql = "SELECT u FROM League\Entity\Match u 
               WHERE u._lid= :lid AND u._resultId IS NULL 
               AND u._date >= :today ORDER BY u._date ASC";
       
       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('lid', $league->getId())
                   ->setParameter('today', $today)
                   ->setMaxResults(3)
                   ->getResult();
       
    }
    
    
    /**
     * Get all open results in the actual season.
     * It may happen to be more than one league only 
     * 
     * @param \League\Entity\Season $season
     * @return array Match
     */
    public function getAllOpenResults(Season $season)
    {
        
       $dql = "SELECT u FROM 
               League\Entity\Match u,
               League\Entity\League l
               WHERE l._sid = :sid AND 
               u._lid=l._id AND 
               u._resultId IS NULL 
               AND u._date < :today 
               ORDER BY u._date ASC";
       
       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('today', new \DateTime())
                   ->setParameter('sid', $season->getId())        
                   ->getResult();
    }
    
    /**
     * get next game
     * 
     * @return array of entities
     */
    public function getNextGame()
    {
       //todo: leagueID
       //todo: find only one result 
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Match')
                   ->findBy(
                        array('_lid' => 1, '_resultId' => NULL), 
                        array('_date'=> 'ASC'),
                        1    
                    );
       
    }
    
    /**
     * get the match by id
     * 
     * @param int $id
     * @return League\Entity\Match
     */
    public function getMatch($id)
    {
       
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Match')
                   ->find($id);
               
    }  
    
    /**
     * set the match result
     * 
     * @param int $id
     * @return League\Entity\Match
     */
    public function setMatchResult(
            $matchId, 
            $resultId, 
            $winnerId, 
            $points=null
            )
    {
       
        $match = $this->getMatch($matchId);
        
        if($match==null)
            return;
        
        $match->setResultId($resultId);
        $match->setWinnerId($winnerId);
        
        if(isset($points))
            $match->setPoints($points);
        
        return $match;
    }      
    
    
}

?>
