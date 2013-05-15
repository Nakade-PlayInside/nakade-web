<?php
namespace League\Business;

use League\Business\Results as RESULT;
use RuntimeException;

/**
 * Description of Results
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MatchStats 
{
    
    protected $_all_matches;
    protected $_playerId;
    
    protected $_hasStats=false;
    protected $_games_played=0;
    protected $_games_suspended=0;
    protected $_games_draw=0;
    protected $_games_won=0;
    protected $_games_lost=0;
    protected $_games_points=0;
    protected $_first_tiebreaker=0;
    protected $_second_tiebreaker=0;
    
    public function __construct($allMatches)
    {
        $this->_all_matches = $allMatches;
    }        
    
    public function setPlayerId($playerId) 
    {
        $this->_hasStats=false;
        $this->_playerId = $playerId;
        return $this;
    }
    
    public function getPlayerId() 
    {
        return $this->_playerId;
    }
    
    
    public function getGamesStats($playerId) 
    {
        $this->setPlayerId($playerId);
        $this->_hasStats=$this->makeCountings();
        
        return array(
            'gamesPlayed'    => $this->_games_played,
            'gamesSuspended' => $this->_games_suspended,
            'gamesWin'       => $this->_games_won,
            'gamesDraw'      => $this->_games_draw,
            'gamesLost'      => $this->_games_lost,
            'gamesPoints'    => $this->_games_points,
            'firstTiebreak'  => $this->_first_tiebreaker,
            'secondTiebreak' => $this->_second_tiebreaker,
        );
    }
    
    public function getPlayedGames() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_games_played;
    }
    
    public function getSuspendeGames() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_games_suspended;
    }
    
    public function getWonGames() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_games_won;
    }
    
    public function getDrawGames() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_games_draw;
    }
    
    public function getLostGames() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_games_lost;
    }
    
    public function getPoints() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_games_points;
    }
    
    
    public function getFirstTiebreak() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_first_tiebreaker;
    }
    
    public function getSecondTiebreak() 
    {
        if (!$this->_hasStats) {
            throw new RuntimeException (
                sprintf(
                    '%s cannot return data as match poll has not yet occurred',
                __METHOD__
            ));
        }
        return $this->_second_tiebreaker;
    }
    
    protected function makeCountings()
    {
        $this->countDrawGames();
        $this->countLostGames();
        $this->countPlayedGames();
        $this->countSuspendedGames();
        $this->countWonGames();
        $this->countPoints();
        $this->countFirstTiebreaker();
        $this->countSecondTiebreaker();
        return true;
    }      
    
    protected function countPlayedGames() {
        
        $playerId = $this->getPlayerId();
        $count=0;
        foreach($this->_all_matches as $match) {
            
            if($match->getResultId() ===null    ||
               $match->getResultId()==RESULT::SUSPENDED) {
               continue;
            }    
     
            if( $match->getBlackId()==$playerId || 
                $match->getWhiteId()==$playerId ) {
                
                $count++;
            }    
            
        } 
      
        $this->_games_played=$count;
    }
    
    protected function countDrawGames() {
        
        $playerId = $this->getPlayerId();
        $count=0;
        foreach($this->_all_matches as $match) {
            
            if($match->getResultId()===null    ||
               $match->getResultId()!=RESULT::DRAW) {
                continue;
            }    
     
            if( $match->getBlackId()==$playerId || 
                $match->getWhiteId()==$playerId ) {
                
                $count++;
            }    
            
        } 
      
        $this->_games_draw=$count;
    }
    
    protected function countWonGames() {
        
        $playerId = $this->getPlayerId();
        $count=0;
        foreach($this->_all_matches as $match) {
            
            if( $match->getResultId()===null        ||
                $match->getResultId()==RESULT::DRAW || 
                $match->getResultId()==RESULT::SUSPENDED ) {
                
                continue;
            }    
   
            if($match->getWinnerId()==$playerId) {
                $count++;
            }    
            
        } 
      
        $this->_games_won=$count;
    }
    
    protected function countLostGames() {
        
        $playerId = $this->getPlayerId();
        $count=0;
        foreach($this->_all_matches as $match) {
            
            if( $match->getResultId()===null             || 
                $match->getResultId()==RESULT::DRAW      || 
                $match->getResultId()==RESULT::SUSPENDED ||
                $match->getWinnerId()==$playerId    
            ) {
                continue;
            }    
     
            
            if( $match->getBlackId()==$playerId || 
                $match->getWhiteId()==$playerId ) {
                
                $count++;
            }    
            
            
        } 
      
        $this->_games_lost=$count;
    }
    
    protected function countSuspendedGames() {
    
        $playerId = $this->getPlayerId();
        $count=0;
        foreach($this->_all_matches as $match) {
            
            $blackId = $match->getBlackId();
            $whiteId = $match->getWhiteId();
            
            if($match->getResultId() ===null ||
               $match->getResultId()!=RESULT::SUSPENDED ) {
                continue;
            }    
     
            if($blackId==$playerId || $whiteId==$playerId) {
                $count++;
            }    
            
        } 
      
        $this->_games_suspended=$count;
    }
    
    protected function countPoints() {
    
         $this->_games_points = 
                 
                 $this->_games_won * 2 + $this->_games_draw;
      
    }
    
    protected function countFirstTiebreaker() {
    
        $playerId = $this->getPlayerId();
        $count=0;
        foreach($this->_all_matches as $match) {
            
            
            if($match->getResultId() ===null            ||
               $match->getResultId()==RESULT::SUSPENDED || 
               $match->getResultId()==RESULT::DRAW) {
               continue;
            }    
     
            if($match->getResultId()==RESULT::BYPOINTS   &&
               $match->getWinnerId()==$playerId ) {
                
               $count += $match->getPoints()+20; 
               continue;
            }
            
            if($match->getResultId()==RESULT::BYPOINTS   &&
               $match->getPoints() < 20                  &&     
               ($match->getBlackId()==$playerId || 
                $match->getWhiteId()==$playerId )) {
                
                
               $count += $match->getPoints(); 
               continue;
            }
            
            if($match->getWinnerId()==$playerId ) {
                
               $count +=40;
               
            }
               
            
        } 
      
        $this->_first_tiebreaker=$count;
    }
    
    protected function countSecondTiebreaker() {
    
        $playerId = $this->getPlayerId();
        $count=0;
        $cuss=0;
        foreach($this->_all_matches as $match) {
            
            
            if($match->getResultId() ===null            ||
               $match->getResultId()==RESULT::SUSPENDED ) {
                
               continue;
            }    
     
            if($match->getWinnerId()==$playerId    ||
               $match->getResultId()==RESULT::DRAW ) {
                
               $count++; 
               $cuss += $count;
               continue;
            }
            
            $cuss += $count;
        } 
      
        $this->_second_tiebreaker=$cuss;
    }
    
}

?>
