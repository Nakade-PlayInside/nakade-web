<?php
namespace League\Controller\Plugin;

use League\Entity\League;
use League\Entity\Season;
use League\Entity\Match;
use League\Entity\Table;

/**
 * PlugIn for managing table database requests.
 *  
 */
class TablePlugin extends AbstractEntityPlugin
{
   
   //maximum points hahn-system
     protected $_max_points = 40; 
    
   /**
   * Getting table of the league sorted by wins,
   * tiebreakers and number of games played.
   * 
   * @param League $league League entity
   * @return array Table entities
   */
   public function getTableByLeagueId($leagueId)
   {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Table')
                   ->findBy(
                        array('_lid' => $leagueId), 
                        array(
                            '_win'=> 'DESC', 
                            '_tiebreaker1'=> 'DESC',
                            '_tiebreaker2'=> 'DESC',
                         )
                     );

    }

   /**
   * Getting table of the league sorted by wins,
   * tiebreakers and number of games played.
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
                 AND l._number=1";
       
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
   
  
    public function setBlackResults($match)
    {
        $leagueId = $match->getLid();
        $uid      = $match->getBlackId();
        $player   = $this->getPlayerStatsInLeague($uid, $leagueId);
        
        return $player;
        return $this->setPlayerResult($match, $player);
        
    }
  
    public function setWhiteResults(Match $match)
    {
      
        $leagueId = $match->getLid();
        $uid      = $match->getWhiteId();
        $player   = $this->getPlayerStatsInLeague($uid, $leagueId);
        
        return $this->setPlayerResult($match, $player);
    }
  /*
    public function setPlayerResult(Match $match, Table $player)
    {
      
        $resultId     = $match->getResultId();
        $winnerId     = $match->getWinnerId();
        $points       = 0.5;
        
        if(isset($match->getPoints()))
                $points = $match->getPoints();
        
        $hasResult    = $resultId!=5;
        $isJigo       = $resultId==3;
        $hasWinner    = $hasResult && !$isJigo; //important
        $isResigned   = ($resultId==1 || $resultId==4);
        $tiebreaker1  = $player->getTiebreaker1();
        
        //game was played
        if($this->isGamePlayed($resultId))
            $this->setGamePlayed($player);
        else 
            $this->setGameSuspended($player);
        
        //jigo
        if($this->isJigo($resultId))
            $this->setJigoResult($player);
        
        //winner or looser
        if($this->hasWinner($resultId))
            $this->setWinnerResult($winnerId, $player);
        
        
        //game was jigo
        if($isJigo) {
            //points for jigo
            $tiebreaker1 += $this->getJigoPoints();
        }
        
        //set winner point
        if($isWinner) {
            //points for resign
            if($isResigned)
                $tiebreaker1 += $this->getResignationPoints();
            else //by points
                $tiebreaker1 += $this->getPoints($points);
        }
        
        //set looser point
        if($isLooser) {
            //points for resign
            if($isResigned)
                $tiebreaker1 += $this->getResignationPoints(false);
            else //by points
                $tiebreaker1 += $this->getPoints($points, false);
        }
       
        //set tiebreaker 1
        $player->setTiebreaker1($tiebreaker1);
        
        return $player;
       
    } 

    public function getHahnPoints() 
    {
        ;
    }        
    
    public function hasWinner($resultId)
    {
        switch($resultId) {
            case 1:
            case 2:
            case 4: return true;
        }
        return false; 
    }   
    
    public function setWinnerResult($winnerId, Table &$player)
    {
        //winner of the game or looser
        if($player->getUid()==$winnerId) 
            $player->setWin($player->getWin()++);
        else
            $player->setLoss($player->getLoss()++);
           
    }
   
    public function isJigo($resultId)
    {
        return $resultId==3;
    }   
    
    public function setJigoResult(Table &$player)
    {
        //game was jigo
        $player->setJigo ($player->getJigo()++);
           
    }
    
    public function isGamePlayed($resultId)
    {
        return $resultId!=5;
    }
    
    public function setGamePlayed(Table &$player)
    {
        //game was played
        $player->setGamesPlayed ($player->getGamesPlayed()++);
    }
    
    public function setGameSuspended(Table &$player)
    {
        $player->setGamesSuspended ($player->getGamesSuspended()++);
    }
    
    public function getJigoPoints()
    {
        return $this->_max_points/2;
    }
    
    public function getResignationPoints($isWinner=true)
    {
        if($isWinner)
            return $this->_max_points;
        
        return 0;
    }
    
    public function getPoints($points, $isWinner=true)
    {
        if($isWinner)
            return $this->_max_points/2 + $points;
        
        return $this->_max_points/2 - $points;
    }
*/
}


