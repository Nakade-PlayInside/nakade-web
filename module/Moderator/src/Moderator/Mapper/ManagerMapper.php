<?php
namespace Moderator\Mapper;

use Moderator\Pagination\ModeratorPagination;
use Moderator\Pagination\TicketPagination;
use Nakade\Abstracts\AbstractMapper;
use \Doctrine\ORM\Query;
use \Permission\Entity\RoleInterface;

/**
 * Class ManagerMapper
 *
 * @package Moderator\Mapper
 */
class ManagerMapper extends AbstractMapper implements RoleInterface
{

    /**
     * @return array
     */
    public function getLeagueManager()
    {
        return $this->getEntityManager()
            ->getRepository('Moderator\Entity\LeagueManager')
            ->findAll();
    }

    /**
     * @param int $offset
     *
     * @return array
     */
    public function getLeagueManagerByPages($offset)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('LeagueManager')
            ->select('l')
            ->from('Moderator\Entity\LeagueManager', 'l')
            ->setFirstResult($offset)
            ->setMaxResults(ModeratorPagination::ITEMS_PER_PAGE);

        return $qb->getQuery()->getResult();
    }


    /**
     * @param int $leagueManagerId
     *
     * @return \Moderator\Entity\LeagueManager
     */
    public function getLeagueManagerById($leagueManagerId)
    {
        return $this->getEntityManager()
            ->getRepository('Moderator\Entity\LeagueManager')
            ->find($leagueManagerId);
    }

    /**
     * @return array
     */
    public function getAssociations()
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Association')
            ->findAll();
    }

    /**
     * @return array
     */
    public function getAvailableManager()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->where('u.role = :user OR u.role = :member')
            ->andWhere('u.active = true')
            ->setParameter('user', self::ROLE_USER)
            ->setParameter('member', self::ROLE_MEMBER);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getSupportRequestsByUser($userId)
    {
        return $this->getEntityManager()
            ->createQueryBuilder('User')
            ->select('s')
            ->from('Moderator\Entity\SupportRequest', 's')
            ->innerJoin('s.creator', 'User')
            ->where('User.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('s.createDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $offset
     *
     * @return array
     */
    public function getSupportRequestsByPages($offset)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Tickets')
            ->select('s')
            ->from('Moderator\Entity\SupportRequest', 's')
            ->setFirstResult($offset)
            ->setMaxResults(TicketPagination::ITEMS_PER_PAGE)
            ->orderBy('s.id', 'ASC')
            ->addOrderBy('s.stage', 'ASC');
//todo: validate correct order
        //todo: LM tickets only
        //todo: edit paginator request
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $ticketId
     *
     * @return \Moderator\Entity\SupportRequest
     */
    public function getTicketById($ticketId)
    {
        return $this->getEntityManager()
            ->getRepository('Moderator\Entity\SupportRequest')
            ->find($ticketId);
    }

    /**
     * @return array
     */
    public function getAssociationsByUser()
    {
        //todo: particicpant is user, season is onGoing -> all associations
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Association')
            ->findAll();
    }

}

