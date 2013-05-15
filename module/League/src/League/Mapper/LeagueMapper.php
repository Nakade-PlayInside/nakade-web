<?php
namespace League\Mapper;


/**
 * Description of LeagueMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class LeagueMapper extends AbstractMapper 
{
    protected $_entity_manager;
    
    public function __construct($em) 
    {
        $this->_entity_manager=$em;
    }

   /**
     * Getting the LeagueId 
     * 
     * @param int $seasonId
     * @param int $number league number
     * @return /League/Entity/League $league
     */
    public function getLeague($seasonId, $number)  
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\League')
                   ->findOneBy(
                        array(
                           '_sid'   => $seasonId,
                           '_number' => $number,
                        )
                     );
    }
    
}
 
?>
