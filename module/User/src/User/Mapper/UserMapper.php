<?php
namespace User\Mapper;


/**
 * Description of UserMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class UserMapper extends AbstractMapper 
{
    
    protected $_entity_manager;
    
    public function __construct($em) 
    {
        $this->_entity_manager=$em;
    }

   /**
     * Get all registered User 
     * 
     * @return /League/Entity/League $league
     */
    public function getAllUser()  
    {
       return $this->getEntityManager()
                   ->getRepository('User\Entity\User')
                   ->findAll();
    }
    
   
}
 
?>
