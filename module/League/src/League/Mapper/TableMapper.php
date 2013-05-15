<?php
namespace League\Mapper;

use League\Entity\League;
use League\Entity\Season;
use League\Entity\Match;
use League\Entity\Table;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TableMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class TableMapper extends AbstractMapper 
{
    protected $_entity_manager;
    
    public function __construct($em) 
    {
        $this->_entity_manager=$em;
    }

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
   public function getTableCalcByLeagueId($leagueId)
   {
       

         $allPlayers = 
             
                        
         /*
         $getGamesPlayed = 
         $black = userId && resultId !=5
         $white = userId && resultId !=5
         */
         
         /*
         $getWonGames = 
         $black = userId && resultId !=5 && winner = uid
         $white = userId && resultId !=5 && winner = uid
         */
         
         
         var_dump($result);
    }
}

?>
