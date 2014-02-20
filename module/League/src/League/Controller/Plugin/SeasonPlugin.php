<?php
namespace League\Controller\Plugin;

use League\Entity\Season; 

/**
 * PlugIn for managing season database requests.
 */
class SeasonPlugin extends AbstractEntityPlugin
{
   
   /**
   * Getting the Actual Season which is ongoing season where  
   * matches are still to be played.
   * Probably, the actual season is not the last season.
   * 
   * @return /League/Entity/Season $season
   */
   public function getActualSeason() 
   {
      
      $dql = "SELECT s as actual FROM League\Entity\Season s
              WHERE s._active=1";
      
      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->getOneOrNullResult();
      
      return $result['actual'];
     
   }
    
   /**
   * Getting the Season title as a string exposing
   * the season number and the year eg. '01/2013'
   * 
   * @return string
   */
   public function getSeasonTitle(Season $season) {
        
       return sprintf(
                  " %s %02d/%d",
                  $season->getTitle(),
                  $season->getNumber(), 
                  date_format($season->getYear(), 'Y')
              );
   }
   
   /**
   * Getting the Season short title as a string exposing
   * the season number and the year eg. '01/2013'
   * 
   * @return string
   */
   public function getSeasonShortTitle(Season $season) {
        
       return sprintf(
                  " %02d/%d",
                  $season->getNumber(), 
                  date_format($season->getYear(), 'Y')
              );
   }
   
   /**
    * Getting the last season number from actual or closed season
    * 
    * @return int
    */
   public function getLastSeasonNumber() {
       
       //first
       $dql = "SELECT MAX(s._number) AS last 
               FROM League\Entity\Season s
               WHERE s._active=1 OR
               s._closed=1";
       
       $last = $this->getEntityManager()
                    ->createQuery($dql)
                    ->getSingleScalarResult();
       
       return $last;
   }
   
   /**
   * Get the last completed season. Returns null if no already 
   * completed season exists. 
   * 
   * @return  /League/Entity/Season $season
   */
   public function getLastSeason() {
     
      $dql = "SELECT s as last FROM League\Entity\Season s
              WHERE s._active=0 AND s._closed=1";
      
      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setMaxResults(1)
                     ->getOneOrNullResult();
       
      return $result['last'];
       
   }
   
   /**
   * Get the new season. A new season is the latest,
   * having no actual and closed flag.
   * Returns null if there is no season existing. 
   * 
   * @return  /League/Entity/Season $season
   */
   public function getNewSeason() {
     
       $last = $this->getLastSeasonNumber();
       
       //if no season existing return null
       if(!isset($last)) return null;
       
       
       $dql = "SELECT s as actual FROM League\Entity\Season s
              WHERE s._active=0
              AND s._closed=0";
      
       $result = $this->getEntityManager()
                      ->createQuery($dql)
                      ->getOneOrNullResult();
       
       return $result['actual'];
           
       
   }
   
   /**
    * Get the state of the new season, eg if there are already
    * leagues added, players added or a schedule is calculated 
    * 
    * @param type $noLeagues
    * @param type $noPlayers
    * @param type $noGames
    * @return string
    */
   public function getState($noLeagues, $noPlayers, $noGames) {
       
       $state = $this->translate('waiting for activating');
       if($noLeagues==0)
          return $this->translate('leagues missing');
       
       if($noPlayers==0)
          return $this->translate('players missing');
       
       if($noGames==0)
          return $this->translate('match schedule missing');
       
       return $state;
       
   }
   
   /**
   * Helper for get the default values for the next season, in detail 
   * number, title and year depending on the last season. 
   * Values are presented as an array. 
   * 
   * @return  array
   */
   public function getNewSeasonDefaults() {
     
      $number=1;
      $title ='Bundesliga';
      $date  = new \DateTime();
      
      $last=$this->getLastSeasonNumber();
      
      if(isset($last)) {
          $number +=$last;
          $season = $this->getSeasonByNumber($last);
          $title  = $season->getTitle();
          
          //get last game date and add 2 weeks break minimum
          $lastGame = $this->getController()
                           ->match()
                           ->getLastGameInSeason($season);
          
          $date = new \DateTime($lastGame);
          $date->modify('+2 week');
          
       }    
     
      return array(
          'number' => $number,
          'title'  => $title, 
          'year'   => $date);  
       
   }
   
   /**
   * Get a season by its unique number.
   * 
   * @return  object League\Entity\Season
   */
   public function getSeasonByNumber($number) {
     
      return $this->getEntityManager()
                   ->getRepository('League\Entity\Season')
                   ->findOneBy(array('_number' => $number));
       
   }
   
   /**
    * Helper for i18N. If a translator is set to the controller, the 
    * message is translated.
    *  
    * @param string $message
    * @return string
    */
   protected function translate($message) {
       
       return $this->getController()->translate($message);
   }
    
   
}

?>
