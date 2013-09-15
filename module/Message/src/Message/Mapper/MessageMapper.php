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
       
          
      $result = $this->getEntityManager()
              ->getRepository('Message\Entity\Message')
              ->find($id);
                    
      return $result;
    }
    
    public function getAllRecipients($id)  
    {
      $qb = $this->getEntityManager()
              ->createQueryBuilder()
              ->select('u')
              ->from('User\Entity\User', 'u')
              ->where('u.active = 1')
              ->andWhere('u.verified = 1')
              ->andWhere('u.id != :myself')
              ->setParameter('myself', $id);
             
       return $qb->getQuery()->getResult();
      
    }
    
    public function getUserById($id)  
    {
      $result = $this->getEntityManager()
              ->getRepository('\User\Entity\User')
              ->find($id);
             
       return $result;
      
    }
    
    
      
    
   
}
 
?>
