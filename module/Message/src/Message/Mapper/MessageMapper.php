<?php
namespace Message\Mapper;

use Nakade\Abstracts\AbstractMapper;
use User\Entity\Message;
/**
 * Description of LeagueMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MessageMapper extends AbstractMapper
{


    /**
     * @param int $uid
     *
     * @return array
     */
    public function getActiveMessagesByUser($uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Message')
            ->select('m')
            ->from('Message\Entity\Message', 'm')
            ->join('m.sender', 'Sender')
            ->join('m.receiver', 'Receiver')
            ->andWhere('Sender.id = :uid')
            ->orWhere('Receiver.id = :uid')
            ->setParameter('uid', $uid)
            ->andWhere('m.threadId is null');

        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $mid
     *
     * @return array
     */
    public function getMessagesById($mid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Message')
            ->select('m')
            ->from('Message\Entity\Message', 'm')
            ->Where('m.id = :mid')
            ->orWhere('m.threadId = :mid')
            ->setParameter('mid', $mid)
            ->orderBy('m.sendDate', DESC);

        return $qb->getQuery()->getResult();
    }

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
