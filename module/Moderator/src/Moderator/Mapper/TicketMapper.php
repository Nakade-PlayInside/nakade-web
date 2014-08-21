<?php
namespace Moderator\Mapper;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Nakade\Pagination\ItemPagination;

/**
 * Class ManagerMapper
 *
 * @package Moderator\Mapper
 */
class TicketMapper extends DefaultMapper
{

    private $where = 'Where';

    /**
     * @param int $userId
     * @param int $offset
     *
     * @return array
     */
    public function getSupportRequestsByPages($userId, $offset=null)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Tickets')
            ->select('s')
            ->from('Moderator\Entity\SupportRequest', 's')
            ->innerJoin('s.creator', 'User')
            ->where('User.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('s.stage', 'ASC')
            ->addOrderBy('s.id', 'DESC');

        if (isset($offset)) {
            $qb->setFirstResult($offset)
                ->setMaxResults(ItemPagination::ITEMS_PER_PAGE);
        }

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
     * get all tickets for moderators, referees, league managers or its owning user.
     * composed request depending on roles.
     *
     * @param int $userId
     * @param int $offset
     *
     * @return array
     */
    public function getTicketsByPages($userId, $offset=null)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Tickets')
            ->select('s')
            ->from('Moderator\Entity\SupportRequest', 's')
            ->innerJoin('s.type', 'type')
            ->orderBy('s.stage', 'ASC')
            ->addOrderBy('s.id', 'DESC');

        if (isset($offset)) {
            $qb->setFirstResult($offset)
               ->setMaxResults(ItemPagination::ITEMS_PER_PAGE);
        }

        $this->addAdminTickets($qb, $userId);
        $this->addRefereeTickets($qb, $userId);
        $this->addLeagueManagerTickets($qb, $userId);

        //todo: edit paginator request
        return $qb->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param int          $userId
     */
    private function addAdminTickets(QueryBuilder $queryBuilder, $userId)
    {
        $where = $this->getWhere();
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();

        if ($this->isAdmin($userId)) {

            $queryBuilder->$where("$alias.type = :adminTicketId")
                ->setParameter('adminTicketId', self::ADMIN_TICKET);
            $this->shiftWhere();
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param int          $userId
     */
    private function addRefereeTickets(QueryBuilder $queryBuilder, $userId)
    {
        $where = $this->getWhere();
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();

        if ($this->isReferee($userId)) {

            $queryBuilder->$where("$alias.type = :refereeTicketId")
                ->setParameter('refereeTicketId', self::REFEREE_TICKET);
            $this->shiftWhere();
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param int          $userId
     */
    private function addLeagueManagerTickets(QueryBuilder $queryBuilder, $userId)
    {
        $associationIds = array();
        if ($this->isLeagueManager($userId)) {

            $associationIds = $this->getIdsOfAssociationsByLeagueManager($userId);
        } elseif ($this->isOwner($userId)) {

            $associationIds = $this->getIdsOfAssociationsByOwner($userId);
        }
        $this->addTicketsByAssociationIds($queryBuilder, $associationIds);
    }

    /**
     * notIn method
     *
     * @param int $userId
     *
     * @return array
     */
    private function getIdsOfAssociationsByLeagueManager($userId)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Association')
            ->select('a.id')
            ->from('Season\Entity\Association', 'a')
            ->leftJoin('Moderator\Entity\LeagueManager', 'l', Join::WITH, 'l.association = a')
            ->innerJoin('l.manager', 'u')
            ->where('l.association = a')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        return $this->getIdArray($result);
    }


    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $associationIds
     */
    private function addTicketsByAssociationIds(QueryBuilder $queryBuilder, array $associationIds)
    {

        $where = $this->getWhere();
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();

        $tickets = $this->getTicketsByAssociationIds($associationIds);

        $queryBuilder->$where($queryBuilder->expr()->in("$alias.id", $tickets));
        $this->shiftWhere();
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    private function getTicketsByAssociationIds(array $ids)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Tickets');

        $qb->select('s.id')
            ->from('Moderator\Entity\SupportRequest', 's')
            ->leftJoin('Season\Entity\Association', 'a', Join::WITH, 's.association = a')
            ->where('s.association = a')
            ->andWhere($qb->expr()->in('a.id', $ids));

        $result = $qb->getQuery()->getResult();

        return $this->getIdArray($result);
    }

    /**
     * @return string
     */
    private function getWhere()
    {
        if (is_null($this->where)) {
            $this->where = $this->initWhere();
        }
        return $this->where;
    }

    /**
     * @return string
     */
    private function shiftWhere()
    {
        return $this->where = 'orWhere';
    }

    /**
     * @return string
     */
    private function initWhere()
    {
        return $this->where = 'Where';
    }

}

