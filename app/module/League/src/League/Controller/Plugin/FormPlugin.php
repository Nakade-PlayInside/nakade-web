<?php
namespace League\Controller\Plugin;
use League\Entity\Match;

class FormPlugin extends AbstractEntityPlugin
{
   
   
   /**
   * Getting the Actual Season
   * 
   * @return /League/Entity/Season $season
   */
   public function getForm()
    {
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Position'
       );
       
       $position = $repository->findBy(
           array('_lid' => 1), 
           array(
              '_win'=> 'DESC', 
              '_tiebreaker1'=> 'DESC',
              '_tiebreaker1'=> 'DESC',
              '_gamesPlayed'=> 'DESC',
              '_id'=> 'DESC'
              )
       );
       
       return $position;
       
    }
    
    
    /**
   * Getting the Actual Season
   * 
   * @return /League/Entity/Season $season
   */
   public function getBlack(Match $match)
    {
       
       return $this->getController()->table()->getBlack($match);
       
       
    }
    
     /**
   * Getting the Actual Season
   * 
   * @return /League/Entity/Season $season
   */
   public function getWhite(Match $match)
    {
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Position'
       );
       
       $black= $repository->findOneBy(
                array('_uid' => $match->getWhiteId())
                );
       
       return $black;
       
    }
    
}

?>
