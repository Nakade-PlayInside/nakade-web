<?php
namespace League\Mapper;

use League\Entity\Season;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlayerMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PlayerMapper extends AbstractMapper 
{
    protected $_entity_manager;
    
    public function __construct($em) 
    {
        $this->_entity_manager=$em;
    }
    
    
    public function getAllPlayersInLeague($leagueId)
    {        
         return $this->getEntityManager()
                     ->getRepository('League\Entity\Participants')
                     ->findBy(array('_lid' => $leagueId)); 
    } 
}

?>
