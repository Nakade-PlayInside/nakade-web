<?php
namespace League\Helper;
use League\Entity\Position as Position;


class PositionCalculator
{
   
    //maximum points hahn-system
    const MAX_POINTS = 20;   
    
    protected $_Entity;
    protected $_resultId;
    protected $_winnerId;
    protected $_points;
    
    /**
     * constructor expecting an array filled with result data from
     * the pairing. Data are presented by ResultForm and given by the
     * user.
     *  
     * @param array $data ResultForm Post Data
     * @access public
     * 
     */
    public function __construct($data = array()) {
        
        $this->_resultId = (isset($data['result']))? 
                $data['result']:null;
        
        $this->_winnerId = (isset($data['winner']))? 
                $data['winner'] : null;
        
        $this->_points   = (isset($data['points'])) ? 
                $data['points'] : null;  
        
    }
    
    /**
     * normalizing points if value exceeds the maximum. 
     * 
     * @access public
     * 
     */
    public function nomalize(){
        
        //normalizing points
        if($this->_points > self::MAX_POINTS)
            $this->_points=self::MAX_POINTS;
    }
    
    /**
     * binding Position entity to the calculated data 
     * and populating it 
     * 
     * @access public
     * @param Position $entity object
     * 
     */
    public function bindEntity(Position &$entity){
        
        $this->_Entity = $entity;
        $this->nomalize();
        $this->_Entity->populate($this->getData());
        
    }
    
    /**
     * getter for the resultId 
     * 
     * @access public
     * @return int resultId
     * 
     */
    public function getResultId(){
        return $this->_resultId;
    }
    
    /**
     * getter for the winnerId 
     * 
     * @access public
     * @return int winnerId
     * 
     */
    public function getWinnerId(){
        return $this->_winnerId;
    }
    
    /**
     * getter for the points 
     * 
     * @access public
     * @return int points
     * 
     */
    public function getPoints(){
        return $this->_points;
    }
    
    /**
     * calculating Data for positioning 
     * 
     * @return array
     */
    public function getData(){
        
        $data = array();    
    
        //game played NOT suspended
        if(isset($this->_resultId) && $this->_resultId!=5){
            $data['gamesPlayed'] = 1;
        }
                
        //game suspended
        if(isset($this->_resultId) && $this->_resultId==5){
            $data['gamesSuspended'] = 1;
        }    
        
        //jigo
        if(isset($this->_resultId) && $this->_resultId==3){
            $data['jigo'] = 1;
        }
                
        //win
        if(isset($this->_winnerId) && 
                $this->_winnerId == $this->_Entity->getUid()){
            
             $data['win'] = 1;   
        }
        
        //loss
        if(isset($this->_winnerId) && 
                $this->_winnerId != $this->_Entity->getUid()){
            
             $data['loss'] = 1;   
        }
        
        //won by resignation
        if(isset($this->_winnerId) && 
                $this->_winnerId == $this->_Entity->getUid() &&
                $this->_resultId == 1){
            
             $data['tiebreaker1'] = 2 * self::MAX_POINTS;   
        }
        
        //won by points
        if(isset($this->_winnerId) && 
                $this->_winnerId == $this->_Entity->getUid() &&
                $this->_resultId == 2){
           
              $data['tiebreaker1'] = $this->_points + self::MAX_POINTS;
        } 
          
        //lost by points
        if(isset($this->_winnerId) && 
                $this->_winnerId != $this->_Entity->getUid() &&
                $this->_resultId == 2){
           
              $data['tiebreaker1'] = self::MAX_POINTS - $this->_points;
        }     
        
        return $data;
    }
    
}

?>
