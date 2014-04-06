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
    public function getInboxMessages($uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Message')
            ->select('m')
            ->from('Message\Entity\Message', 'm')
            ->join('m.receiver', 'Receiver')
            ->andWhere('Receiver.id = :uid')
            ->andWhere('m.hidden = 0')
            ->setParameter('uid', $uid)
            ->orderBy('m.sendDate', DESC);

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param int $uid
     *
     * @return array
     */
    public function getSentBoxMessages($uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Message')
            ->select('m')
            ->from('Message\Entity\Message', 'm')
            ->join('m.sender', 'Sender')
            ->andWhere('Sender.id = :uid')
            ->setParameter('uid', $uid)
            ->orderBy('m.sendDate', DESC);

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param int $uid
     * @param int $messageId
     *
     * @return bool
     */
    public function hideMessageByUser($uid, $messageId)
    {
        $message = $this->getMessageById($messageId);

        if ($message->getReceiver()->getId() == $uid) {
            $message->setHidden(true);
            $this->update($message);
            return true;
        }

        return false;

    }

    /**
     * @param int $mid
     *
     * @return \Message\Entity\Message
     */
    public function getMessageById($mid)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('Message\Entity\Message')->findOneBy(array('id' => $mid));

    }


    /**
     * @param int $mid
     *
     * @return array
     */
    public function getAllMessagesById($mid)
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


    /**
     * @param int $messageId
     *
     * @return \Message\Entity\Message
     */
    public function getLastMessageById($messageId)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('m')
            ->from('Message\Entity\Message', 'm')
            ->Where('m.id = :mid')
            ->orWhere('m.threadId = :mid')
            ->setParameter('mid', $messageId)
            ->orderBy("m.sendDate", DESC)
            ->setMaxResults(1);


        $result = $qb->getQuery()->getResult();
        return $result[0];

    }

    /**
     * @param int $uid
     *
     * @return array
     */
    public function getAllRecipients($uid)
    {
      $qb = $this->getEntityManager()
              ->createQueryBuilder()
              ->select('u')
              ->from('User\Entity\User', 'u')
              ->where('u.active = 1')
              ->andWhere('u.verified = 1')
              ->andWhere('u.id != :myself')
              ->setParameter('myself', $uid);

       return $qb->getQuery()->getResult();

    }


    /**
     * @param int $uid
     *
     * @return \User\Entity\User
     */
    public function getUserById($uid)
    {
      $result = $this->getEntityManager()
              ->getRepository('\User\Entity\User')
              ->find($uid);

       return $result;

    }

    /**
     * @param int $uid
     *
     * @return array
     */
    public function getNumberOfNewMessages($uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Message')
            ->select('m')
            ->from('Message\Entity\Message', 'm')
            ->join('m.receiver', 'Receiver')
            ->andWhere('Receiver.id = :uid')
            ->andWhere('m.hidden = 0')
            ->andWhere('m.new = 1')
            ->setParameter('uid', $uid);

        $result = $qb->getQuery()->getResult();

        return count($result);
    }




}

?>
