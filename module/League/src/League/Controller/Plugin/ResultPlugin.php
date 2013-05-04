<?php
namespace League\Controller\Plugin;

use League\Entity\League;
use League\Entity\Season;

class ResultPlugin extends AbstractEntityPlugin
{
    public function getResultlist()
    {
       
        
       $repository = $this->getEntityManager()->getRepository(
           'League\Entity\Result'
       );
       
       
       //@todo: datumsvergleich jetzt zu nächsten termin
       //@todo: nur aktuelle Termine, nicht die Spiele,
       //die noch nicht eingetragen sind 
       
       $game = $repository->findAll();
       
       return $game;
       
    } 
    
}

?>
