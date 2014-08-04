<?php
namespace Message\Mapper;

use Doctrine\ORM\Query\Expr\Join;
use Message\Pagination\MessagePagination;
use Nakade\Abstracts\AbstractMapper;
use \Doctrine\ORM\Query;
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
            ->orderBy('m.sendDate', 'DESC');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param int $uid
     * @param int $offset
     *
     * @return array
     */
    public function getUserMessagesByPages($uid, $offset)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Message')
            ->select('m')
            ->from('Message\Entity\Message', 'm')
            ->join('m.receiver', 'Receiver')
            ->andWhere('Receiver.id = :uid')
            ->andWhere('m.hidden = 0')
            ->setParameter('uid', $uid)
            ->setFirstResult($offset)
            ->setMaxResults(MessagePagination::ITEMS_PER_PAGE)
            ->orderBy('m.sendDate', 'DESC');

        return $qb->getQuery()->getResult();
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
            ->orderBy('m.sendDate', 'DESC');

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
            ->orderBy('m.sendDate', 'DESC');

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
            ->orderBy("m.sendDate", 'DESC')
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

        //get my leagues
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('l.id')
            ->from('Season\Entity\League', 'l')
            ->leftJoin('Season\Entity\Match',  'm', Join::WITH, 'm.league = l')
            ->innerJoin('m.white', 'White')
            ->innerJoin('m.black', 'Black')
            ->innerJoin('l.season', 's')
            ->where('(White.id = :uid OR Black.id = :uid)')
            ->andWhere('s.isReady = 1')
             ->andWhere('m.date > :now')
            ->addGroupBy('l')
            ->setParameter('uid', $uid)
            ->setParameter('now', new \DateTime);

        $myLeagues = $qb->getQuery()->getResult();

        //quicker than array_map
        $leagueIds = array();
        foreach ($myLeagues as $item) {
            $leagueIds[] = $item['id'];
        }

        //get my opponents
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Participant',  'p', Join::WITH, 'p.user = u')
            ->innerJoin('p.league', 'league')
            ->where($qb->expr()->in('league.id', $leagueIds))
            ->andWhere('u.id != :mySelf')
            ->setParameter('mySelf', $uid)
            ->addGroupBy('u');

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
