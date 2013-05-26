<?php
namespace League\Statistics\Sorting;


/**
 * Sorting an array of objects in a dependend order. After the first sorting
 * equals are sorted by points and its tiebreakers.
 * Next to it, all sorting patterns are provided as constants.
 * This is a singleton.
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PlayerSorting
{
    const BY_POINTS='points';
    const BY_NAME='name';
    const BY_SUSPENDED_GAMES='suspended';
    const BY_PLAYED_GAMES='played';
    const BY_WON_GAMES='win';
    const BY_LOST_GAMES='lost';
    const BY_DRAW_GAMES='draw';
    const BY_FIRST_TIEBREAK='firstTiebreak';
    const BY_SECOND_TIEBREAK='secondTiebreak';
    const BY_THIRD_TIEBREAK='thirdTiebreak';
    
    /**
     * instance
     * @var object
     */
    private static $instance =null;
    
    /**
     * static for getting an instance of this class
     * @return PlayerSorting
     */
    public static function getInstance() {
        
        if(self::$instance == null)
            self::$instance=new PlayerSorting ();
        
        return self::$instance;
    }
    
    /**
     * Sorting an array of objetcs
     * @param array $playersInLeague
     * @param string $sort
     */
    public function sorting(&$playersInLeague, $sort=self::BY_POINTS) 
    {
        
       $method = 'sortBy' . ucfirst($sort);  
       if(!method_exists($this, $method))
           $method='sortBy' . ucfirst('points');      
       
       usort($playersInLeague, array($this, $method));
       
    }
    
    protected function sortByName($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('player');
           if(
               strcmp (
                   $comp_a->$method()->getShortName(),
                   $comp_b->$method()->getShortName()
               ) == 0
           ) {
               return $this->sortByPoints($comp_a, $comp_b);
           }
        
           return (
                   strcmp (
                       $comp_a->$method()->getShortName(),
                       $comp_b->$method()->getShortName()
                   )
           );
            
    }
    
    protected function sortBySuspended($comp_a, $comp_b) {
        
        
           $method =  "get" . ucfirst('gamesSuspended');
           if ($comp_a->$method() == $comp_b->$method() ) {
               return $this->sortByPoints($comp_a, $comp_b);
           }    
      
           return ($comp_a->$method() > $comp_b->$method() )?-1:1;
            
    }
    
    
    protected function sortByPlayed($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('gamesPlayed');
           if($comp_a->$method() == $comp_b->$method()) {
               return $this->sortByPoints($comp_a, $comp_b);
           }    
        
           return ($comp_a->$method() > $comp_b->$method() )?-1:1;
            
    }
    
    protected function sortByWin($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('gamesWin');
           if($comp_a->$method() == $comp_b->$method()) {
               return $this->sortByPoints($comp_a, $comp_b);
           }    
        
           return ($comp_a->$method() > $comp_b->$method() )?-1:1;
            
    }
    
    protected function sortByLost($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('gamesLost');
           if($comp_a->$method() == $comp_b->$method() ) {
               return $this->sortByPoints($comp_a, $comp_b);
           }    
        
           return ($comp_a->$method() > $comp_b->$method() )?-1:1;
            
    }
    
    protected function sortByDraw($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('gamesDraw');
           if($comp_a->$method() == $comp_b->$method() ) {
               return $this->sortByPoints($comp_a, $comp_b);
           }    
        
           return ($comp_a->$method()>$comp_b->$method())?-1:1;
            
    }
    
    protected function sortByPoints($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('gamesPoints'); 
           if($comp_a->$method()==$comp_b->$method())
               return $this->sortByFirstTiebreak($comp_a, $comp_b);
        
           return ($comp_a->$method()>$comp_b->$method())?-1:1;
            
    }
  
    protected function sortByFirstTiebreak($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('firstTiebreak'); 
           if($comp_a->$method()==$comp_b->$method())
               return $this->sortBySecondTiebreak($comp_a, $comp_b);
        
           return ($comp_a->$method()>$comp_b->$method())?-1:1;
            
    }

    protected function sortBySecondTiebreak($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('secondTiebreak'); 
           
           if($comp_a->$method() == $comp_b->$method() ) {
               return $this->sortByThirdTiebreak($comp_a, $comp_b);
           }    
        
           return ($comp_a->$method() > $comp_b->$method() )?-1:1;
            
    }
    
    protected function sortByThirdTiebreak($comp_a, $comp_b) {
        
           $method =  "get" . ucfirst('thirdTiebreak'); 
           
           if($comp_a->$method()==$comp_b->$method() ) {
               return 0;
           }    
        
           return ($comp_a->$method() > $comp_b->$method() )?-1:1;
            
    }
    

}

?>
