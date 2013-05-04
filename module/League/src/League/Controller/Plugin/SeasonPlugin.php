<?php
namespace League\Controller\Plugin;

use League\Entity\Season; 

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
    * Getting the last season number
    * 
    * @return int
    */
   public function getLastSeasonNumber() {
       
       //first
       $dql = "SELECT MAX(s._number) AS last FROM League\Entity\Season s";
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
   * Get the latest season which is eventually the actual ongoing
   * season.Returns null if there is no season existing. 
   * 
   * @return  /League/Entity/Season $season
   */
   public function getLatestSeason() {
     
       $last = $this->getLastSeasonNumber();
       
       //if no season existing return null
       if(!isset($last))
           return null;
           
           
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Season')
                   ->findOneBy (array('_number' => $last));
       
   }
    
   
}

?>
