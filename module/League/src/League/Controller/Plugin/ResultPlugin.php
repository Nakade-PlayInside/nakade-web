<?php
namespace League\Controller\Plugin;

/*
 * PlugIn for the result table of the database
 */
class ResultPlugin extends AbstractEntityPlugin
{
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
