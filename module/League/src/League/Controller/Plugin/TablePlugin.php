<?php
namespace League\Controller\Plugin;

use League\Entity\League;
use League\Entity\Season;

class TablePlugin extends AbstractEntityPlugin
{
   
    /**
   * Getting table of the league
   * 
   * @param League $league League entity
   * @return array Table entities
   */
   public function getTable(League $league)
   {
        
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Table')
                   ->findBy(
                        array('_lid' => $league->getId()), 
                        array(
                            '_win'=> 'DESC', 
                            '_tiebreaker1'=> 'DESC',
                            '_tiebreaker2'=> 'DESC',
                            '_gamesPlayed'=> 'DESC'
                        )
                     );

    }
    
    
   /**
   * Get player statistics in league. 
   * 
   * @param int userId
   * @param int LeagueId
   * @return Table entity
   */
   public function getPlayerStatsInLeague($uid, $lid)
   {
        
       return  $this->getEntityManager()
                    ->getRepository('League\Entity\Table')
                    ->findOneBy(
                        array(
                            '_uid' => $uid,
                            '_lid' => $lid,
                        )
                    );
       
   }
   
   /**
    * get top league in a season 
    * 
    * @param \League\Entity\Season $season
    * @return \League\Entity\League or Null
    */
   public function getTopLeagueInSeason(Season $season)
   {
       
       $dql =   "SELECT l FROM League\Entity\League l
                 WHERE l._sid=:sid
                 AND l._order=1";
       
       return  $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $season->getId())
                    ->getOneOrNullResult();
   }        
   
   /**
    * get maximum wins in a league
    * 
    * @param \League\Entity\League $league
    * @return int
    */
   public function getMaxWinsInLeague(League $league)
   {
       
       $dql = "SELECT MAX(t._win) AS win 
               FROM League\Entity\Table t
               WHERE t._lid=:lid";
       
       return  $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('lid', $league->getId())
                    ->getSingleScalarResult();
   }        
   
   /**
    * get most tiebreaking points of all in league with same amount
    * of wins.
    *  
    * @param \League\Entity\League $league
    * @param int $wins
    * @return float
    */
   public function getFirstTiebreaker(League $league, $wins)
   {
       $dql = "SELECT MAX(t._tiebreaker1) AS tb1 
               FROM League\Entity\Table t
               WHERE t._lid=:lid
               AND t._win=:wins";
       
       return  $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('lid', $league->getId())
                    ->setParameter('wins', $wins)
                    ->getSingleScalarResult();
   }
   
   /**
    * get most secondary tiebreaking points of all in league with same amount
    * of wins and same amount of first tiebreaking points.
    *  
    * @param \League\Entity\League $league
    * @param int $wins
    * @param int $tb1
    * @return float
    */
   public function getSecondTiebreaker(League $league, $wins, $tb1)
   {
       
       $dql = "SELECT MAX(t._tiebreaker2) AS tb2 
               FROM League\Entity\Table t
               WHERE t._lid=:lid
               AND t._win=:wins
               AND t._tiebreaker1=:tb1";
       
       return  $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('lid', $league->getId())
                    ->setParameter('wins', $wins)
                    ->setParameter('tb1',  $tb1)
                    ->getSingleScalarResult();
   }
   
   /**
   * Get the champion of the season.
   * Probably more than one.
   * 
   * @param Pairing $match entity
   * @return array of Table entities
   */
   public function getChampion($season)
   {
       
       $league = $this->getTopLeagueInSeason($season);
       $wins   = $this->getMaxWinsInLeague($league);
       $tb1    = $this->getFirstTiebreaker($league, $wins);
       $tb2    = $this->getSecondTiebreaker($league, $wins, $tb1);
       
       $dql="SELECT t FROM League\Entity\Table t
             WHERE t._lid=:lid
             AND t._win=:wins
             AND t._tiebreaker1=:tb1
             AND t._tiebreaker2=:tb2";
        
               
        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('lid', $league->getId())
                    ->setParameter('wins', $wins)
                    ->setParameter('tb1', $tb1)
                    ->setParameter('tb2', $tb2)
                    ->getResult();
       
    }
   
}

?>
