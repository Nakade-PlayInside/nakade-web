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

        foreach ($result as $key => $message) {
            $isNew = $this->isNewMessage($uid, $message->getId());
            $result[$key]->setNew($isNew);
        }

        return $result;
    }

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

        foreach ($result as $key => $message) {
            $isNew = $this->isReadMessage($uid, $message->getId());
            $result[$key]->setRead($isNew);
        }

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
            ->andWhere('m.threadId is null')
            ->orderBy('m.sendDate', DESC);

        $result = $qb->getQuery()->getResult();

        foreach ($result as $key => $message) {
            $isNew = $this->isNewMessage($uid, $message->getId());
            $isRead = $this->isReadMessage($uid, $message->getId());

            $result[$key]->setNew($isNew);
            $result[$key]->setRead($isRead);
        }

        return $result;

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

    /**
     * new message is where the receiver is the user and
     * has not read his message yet
     *
     * @param int $userId
     * @param int $messageId
     *
     * @return bool
     */
    public function isNewMessage($userId, $messageId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('m')
           ->from('Message\Entity\Message', 'm')
           ->Join('m.receiver', 'Receiver')
           ->andWhere('Receiver.id = :uid')
           ->setParameter('uid', $userId)
           ->andWhere($qb
                ->expr()
                ->orX(
                    $qb->expr()->eq('m.id', ':mid'),
                    $qb->expr()->eq('m.threadId', ':mid')
                ))
            ->setParameter('mid', $messageId)
            ->andWhere('m.readDate is null');

        $result = $qb->getQuery()->getResult();
        return count($result)>0;

    }

    /**
     * read message is when the user is the sender and the msg is not read yet
     *
     * @param int $userId
     * @param int $messageId
     *
     * @return bool
     */
    public function isReadMessage($userId, $messageId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('m')
           ->from('Message\Entity\Message', 'm')
           ->join('m.sender', 'Sender')
           ->Where('Sender.id = :uid')
           ->setParameter('uid', $userId)
           ->andWhere($qb
                ->expr()
                ->orX(
                    $qb->expr()->eq('m.id', ':mid'),
                    $qb->expr()->eq('m.threadId', ':mid')
                ))
           ->setParameter('mid', $messageId)
           ->andWhere('m.readDate is not null');

        return count($qb->getQuery()->getResult())>0;

    }

    /**
     * @param int $messageId
     *
     * @return \User\Entity\Message $object
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

    public function getAllMessages()
    {

       return $this->getEntityManager()
                   ->getRepository('Message\Entity\Message')
                   ->findAll();
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
