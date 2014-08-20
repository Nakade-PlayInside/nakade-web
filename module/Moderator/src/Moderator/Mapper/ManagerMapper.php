<?php
namespace Moderator\Mapper;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Moderator\Pagination\ModeratorPagination;
use Moderator\Pagination\TicketPagination;
use Nakade\Abstracts\AbstractMapper;
use \Permission\Entity\RoleInterface;

/**
 * Class ManagerMapper
 *
 * @package Moderator\Mapper
 */
class ManagerMapper extends AbstractMapper implements RoleInterface
{

    /**
     * for ticket mails
     *
     * @param int $associationId
     *
     * @return array
     */
    public function getTicketManagerByAssociation($associationId)
    {
        $leagueManagers = $this->getLeagueManagerByAssociation($associationId);
        $owner = $this->getOwnerByAssociation($associationId);

        return array_merge($leagueManagers, $owner);
    }

    /**
     * get assigned LM
     *
     * @param $associationId
     *
     * @return array
     */
    public function getLeagueManagerByAssociation($associationId)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Moderator\Entity\LeagueManager', 'l', Join::WITH, 'l.manager = u')
            ->innerJoin('l.association', 'a')
            ->Where('l.isActive = true')
            ->andWhere('a.id =:associationId')
            ->setParameter('associationId', $associationId)
            ->andWhere('u.active = true');

        $this->addManagerRoles($qb);
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param $associationId
     *
     * @return array
     */
    public function getOwnerByAssociation($associationId)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Association', 'a', Join::WITH, 'a.owner = u')
            ->Where('a.id =:associationId')
            ->setParameter('associationId', $associationId)
            ->andWhere('u.active = true');

        $this->addOwnerRoles($qb);
        $result = $qb->getQuery()->getResult();

        return $result;
    }


    /**
     * @return array
     */
    public function getAdministrators()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->Where('u.active = true');

        $this->addAdminRoles($qb);
        return $qb->getQuery()->getResult();
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
     * @return array
     */
    public function getReferees()
    {
        return $this->getEntityManager()
            ->getRepository('Moderator\Entity\Referee')
            ->findAll();
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
     * @param int $refereeId
     *
     * @return \Moderator\Entity\Referee
     */
    public function getRefereeById($refereeId)
    {
        return $this->getEntityManager()
            ->getRepository('Moderator\Entity\Referee')
            ->find($refereeId);
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
    {//todo: not self => owner
        $qb = $this->getEntityManager()
            ->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->Where('u.active = true');

        $this->addManagerRoles($qb);
        return $qb->getQuery()->getResult();
    }

    /**
     * notIn method
     *
     * @return array
     */
    private function getIdsOfNominatedReferees()
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Referees')
            ->select('u.id')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Moderator\Entity\Referee', 'r', Join::WITH, 'r.user = u')
            ->where('r.user = u')
            ->getQuery()
            ->getResult();

        return $this->getIdArray($result);
    }

    /**
     * @return array
     */
    public function getAvailableReferee()
    {
        $notIn = $this->getIdsOfNominatedReferees();
        $qb = $this->getEntityManager()->createQueryBuilder('Referees');

        $qb->select('u')
           ->from('User\Entity\User', 'u')
           ->Where($qb->expr()->notIn('u.id', $notIn))
           ->andWhere('u.active = true');

        $this->addRefereeRoles($qb);
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
     * manager ticket overview
     *
     * @param int $offset
     *
     * @return array
     */
    public function getTicketsByPages($offset)
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
     * @param $userId
     *
     * @return bool
     */
    public function isAdmin($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Admin')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->andWhere('u.active = true')
            ->andWhere('u.id = :uid')
            ->setParameter('uid', $userId);

        $this->addAdminRoles($qb);
        $result = $qb->getQuery()->getResult();

        return !empty($result);
    }

    /**
     * @param $userId
     *
     * @return bool
     */
    public function isLeagueManager($userId)
    {

        $qb = $this->getEntityManager()
            ->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Moderator\Entity\LeagueManager', 'l', Join::WITH, 'l.manager = u')
            ->andWhere('l.isActive = true')
            ->andWhere('u.active = true')
            ->setParameter('uid', $userId);

        $this->addManagerRoles($qb);
        $result = $qb->getQuery()->getResult();

        return !empty($result);
    }

    /**
     * @param $userId
     *
     * @return bool
     */
    public function isOwner($userId)
    {

        $qb = $this->getEntityManager()
            ->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Association', 'a', Join::WITH, 'a.owner = u')
            ->Where('u.active = true')
            ->andWhere('u.id = :userId')
            ->setParameter('uid', $userId);

        $this->addOwnerRoles($qb);
        $result = $qb->getQuery()->getResult();

        return !empty($result);
    }

    /**
     * @param $userId
     *
     * @return bool
     */
    public function isReferee($userId)
    {

        $qb = $this->getEntityManager()
            ->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Moderator\Entity\Referee', 'r', Join::WITH, 'r.user = u')
            ->Where('u.active = true')
            ->andWhere('u.id = :userId')
            ->setParameter('uid', $userId);

        $this->addRefereeRoles($qb);
        $result = $qb->getQuery()->getResult();

        return !empty($result);
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

    /**
     * @param QueryBuilder $queryBuilder
     */
    private function addManagerRoles(QueryBuilder &$queryBuilder)
    {
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();
        $queryBuilder
            ->andWhere("$alias.role = :user OR $alias.role = :member")
            ->setParameter('user', self::ROLE_USER)
            ->setParameter('member', self::ROLE_MEMBER);
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    private function addAdminRoles(QueryBuilder &$queryBuilder)
    {
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();
        $queryBuilder
            ->andWhere("$alias.role = :moderator OR $alias.role = :admin")
            ->setParameter('moderator', self::ROLE_MODERATOR)
            ->setParameter('admin', self::ROLE_ADMIN);

    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    private function addOwnerRoles(QueryBuilder &$queryBuilder)
    {
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();
        $queryBuilder
            ->andWhere("$alias.role != :guest")
            ->setParameter('guest', self::ROLE_GUEST);

    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    private function addRefereeRoles(QueryBuilder &$queryBuilder)
    {
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();
        $queryBuilder
            ->andWhere("$alias.role = :member")
            ->setParameter('member', self::ROLE_MEMBER);

    }

    /**
     * @param array $result
     *
     * @return array
     */
    private function getIdArray(array $result) {

        $idArray = array();
        foreach ($result as $item) {
            $idArray[] = $item['id'];
        }
        if (empty($idArray)) {
            $idArray[]=0;
        }

        return array_unique($idArray);
    }
}

