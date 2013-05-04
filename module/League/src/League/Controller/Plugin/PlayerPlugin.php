<?php
namespace League\Controller\Plugin;

use League\Entity\Season;

class PlayerPlugin extends AbstractEntityPlugin
{
    /**
     * get number of players in season
     * 
     * @param League\Entity\Season $season
     * @return int
     */
    public function getNoPlayersInSeason(Season $season)
    {
      
       $query="SELECT p FROM League\Entity\Participants p,
           League\Entity\League l
           WHERE p._lid=l._id
           AND p._sid=:sid" ;
       
       $players = $this->getEntityManager()->createQuery($query);
       $players->setParameter('sid', $season->getId());
    
       return count($players->getResult());
       
    }
}

?>
