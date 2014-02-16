<?php
namespace League\Schedule;

/**
 * Description of Schedule
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class Schedule {
   
    const BYE = 'FREILOS';
    protected $_startdate;
    protected $_isSingleGameAMatchday;
    protected $_week=0;
    
    public function __construct($date, $course=false) {
        
        $this->_isSingleGameAMatchday=$course;
        $this->_startdate = $date; 
    }
    
    public function makeSchedule($teams) {
        
        shuffle($teams);
        
        if(count($teams) % 2 ){ 
            array_push($teams , self::BYE); 
        } 

        $plan       = array();  // Array für den kompletten Spielplan 
        $nogame     = 0;        // Zähler für Spielnummer 
        
    
   
        for ($matchday=1; $matchday<count($teams); $matchday++) { 

          //rearrangement of team array  
          array_splice ($teams, 1, 1, array(array_pop($teams),$teams[1])); 


                for ($nopairing=0; $nopairing<(count($teams)/2); $nopairing++) { 

                    $home =  $teams[$nopairing];
                    $away =  $teams[(count($teams)-1)-$nopairing];

                    if($home == self::BYE || $away == self::BYE ) {
                        continue; 
                    }    

                    $nogame++;

                    //change home and away 
                    if ( ($nogame%count($teams)==1) && ($nopairing%2==0) ) { 
                        $temp = $home;
                        $home = $away; 
                        $away = $temp; 
                    }  

                    $plan[$matchday][$nogame]['away'] = $away;
                    $plan[$matchday][$nogame]['home'] = $home;

                 } 
          } 
          ksort($plan); // nach Spieltagen sortieren 

          $pairing  = $this->makePairings($plan);
          return $pairing;

    }
    
    private function makePairings($plan)
    {
        $pairings = array();
        foreach($plan as $matchday=>$matchdaypairing) {
            
            //each matchday another date
            $date =  $this->getMatchDate($matchday);
            
            foreach($matchdaypairing as $game=>$pairing) {
                
                //each game another date 
                if($this->_isSingleGameAMatchday) {
                    $date = $this->getMatchDate($game);
                }
                
                $data['matchday'] = $matchday;
                $data['league']   = $pairing['away']->getLeague();
                $data['lid']      = $pairing['away']->getLid();
                $data['date']     = $date;
                $data['black']    = $pairing['home']->getPlayer();  
                $data['blackId']  = $pairing['home']->getId();  
                $data['white']    = $pairing['away']->getPlayer();    
                $data['whiteId']  = $pairing['away']->getId();  
                
                $match = new \League\Entity\Match();
                $match->exchangeArray($data);
              
                array_push($pairings, $match);
            }
        }
      
        return $pairings;
        
    }
    
    private function getMatchDate($week) 
    {
       $modify = sprintf('+%s week', $week-1);
       $date = new \DateTime($this->_startdate);
       
       return $date->modify($modify);
       
    }
    
    
    
}

?>
