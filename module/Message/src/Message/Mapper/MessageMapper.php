<?php
namespace Message\Mapper;

use Nakade\Abstracts\AbstractMapper;
/**
 * Description of LeagueMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MessageMapper extends AbstractMapper 
{
   
    public function getMessages()  
    {
       
       return $this->getEntityManager()
                   ->getRepository('Message\Entity\Message')
                   ->findAll();
    }
    
   
}
 
?>
