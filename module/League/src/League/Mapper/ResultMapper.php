<?php
namespace League\Mapper;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResultMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class ResultMapper extends AbstractMapper 
{
    protected $_entity_manager;
    
    public function __construct($em) 
    {
        $this->_entity_manager=$em;
    }
    
    /**
     * get all kind of results 
     * 
     * @return array of Result entity
     */
    public function getResultlist()
    {
        return $this->getEntityManager()
                    ->getRepository('League\Entity\Result')
                    ->findAll();
       
    } 
    
}

?>
