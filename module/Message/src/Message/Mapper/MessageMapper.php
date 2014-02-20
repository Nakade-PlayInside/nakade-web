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
   
    public function getAllMessages()  
    {
       
       return $this->getEntityManager()
                   ->getRepository('Message\Entity\Message')
                   ->findAll();
    }
    
    public function getMessageById($id)  
    {
       
      $dql = "SELECT m as message 
               FROM Message\Entity\Message m
               WHERE m.id=:id";
      
      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('id', $id)
                     ->getOneOrNullResult();
      
      return $result['message'];
    }
    
   
}
 
?>
