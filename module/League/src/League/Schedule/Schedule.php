<?php
namespace League\Schedule;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Schedule
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class Schedule {
   
    const BYE = 'FREILOS';
    protected $_startdate;
    
    public function __construct() {
        
        // todo: startdate option
        $this->_startdate = new \DateTime("2013-08-15 18:00"); 
       
    }
    
    public function makeSchedule($teams) {
        
        if(count($teams) % 2 ){ 
            array_push($teams , self::BYE); 
        } 

        $plan       = array(); // Array für den kompletten Spielplan 
        $nogame     = 0;   // Zähler für Spielnummer 
        
    
   
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
            
            $date =  $this->getMatchDate($matchday);
            
            foreach($matchdaypairing as $game=>$pairing) {
                
                $date =  $this->getMatchDate($game);
                $match = new \League\Entity\Match();
                
                //todo: exchange und populate
                $match->setMatchday($matchday);
                $match->setLid($pairing['away']->getLid());
                $match->setLeague($pairing['away']->getLeague());
                $match->setDate($date);
                $match->setBlackId($pairing['away']->getId());
                $match->setBlack($pairing['away']);
                $match->setWhiteId($pairing['home']->getId());
                $match->setWhite($pairing['home']);
                array_push($pairings, $match);
            }
        }
      
        return $pairings;
        
    }
    
    private function getMatchDate($week) 
    {
       return $this->_startdate->modify('+'.$week. ' week');
    }
    
    
    
}

?>
