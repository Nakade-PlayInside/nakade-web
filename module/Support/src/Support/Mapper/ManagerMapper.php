<?php
namespace Support\Mapper;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Nakade\Pagination\ItemPagination;

/**
 * Class ManagerMapper
 *
 * @package Support\Mapper
 */
class ManagerMapper extends DefaultMapper
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
            ->leftJoin('Support\Entity\LeagueManager', 'l', Join::WITH, 'l.manager = u')
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
     * @param int $userId
     * @param int $offset
     *
     * @return array
     */
    public function getMyLeagueManagersByPages($userId, $offset=null)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('LeagueManager')
            ->select('l')
            ->from('Support\Entity\LeagueManager', 'l')
            ->leftJoin('Season\Entity\Association', 'a', Join::WITH, 'a = l.association')
            ->innerJoin('a.owner', 'user')
            ->where('user.id = :userId')
            ->setParameter('userId', $userId);

        if (isset($offset)) {
            $qb->setFirstResult($offset)
                ->setMaxResults(ItemPagination::ITEMS_PER_PAGE);
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * @param int|null $offset
     *
     * @return array
     */
    public function getRefereesByPages($offset=null)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Referee')
            ->select('r')
            ->from('Support\Entity\Referee', 'r');

        if (isset($offset)) {
            $qb->setFirstResult($offset)
                ->setMaxResults(ItemPagination::ITEMS_PER_PAGE);
        }

        return $qb->getQuery()->getResult();

    }

    /**
     * @return array
     */
    public function getActiveReferees()
    {
        $qb = $this->getEntityManager()->createQueryBuilder('User')
            ->select('u')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Support\Entity\Referee', 'r', Join::WITH, 'r.user = u')
            ->where('r.user = u')
            ->andWhere('r.isActive = true')
            ->andWhere('u.active = true');

        $this->addRefereeRoles($qb);
        return $qb->getQuery()->getResult();
    }


    /**
     * @param int $leagueManagerId
     *
     * @return \Support\Entity\LeagueManager
     */
    public function getLeagueManagerById($leagueManagerId)
    {
        return $this->getEntityManager()
            ->getRepository('Support\Entity\LeagueManager')
            ->find($leagueManagerId);
    }

    /**
     * @param int $refereeId
     *
     * @return \Support\Entity\Referee
     */
    public function getRefereeById($refereeId)
    {
        return $this->getEntityManager()
            ->getRepository('Support\Entity\Referee')
            ->find($refereeId);
    }

    /**
     * @param int $userId
     *
     * @return QueryBuilder
     */
    public function getAssociationsByUser($userId)
    {

        $result = $this->getEntityManager()
            ->createQueryBuilder('Associations')
            ->select('a')
            ->from('Season\Entity\Association', 'a')
            ->innerJoin('a.owner', 'Owner')
            ->Where('Owner.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        return $result;
    }


    /**
     * @param int $userId
     *
     * @return QueryBuilder
     */
    private function getLeagueManagerOnDutyByUser($userId)
    {
        $myAssociation = $this->getIdsOfAssociationsByOwner($userId);
        $qb = $this->getEntityManager()->createQueryBuilder('User');

        $qb->select('u.id')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Support\Entity\LeagueManager', 'l', Join::WITH, 'l.manager = u')
            ->innerJoin('l.association', 'a')
            ->where($qb->expr()->In('a.id', $myAssociation))
            ->andWhere('l.manager = u');

        $result = $qb->getQuery()->getResult();
        return $this->getIdArray($result);
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getAvailableManagerByUser($userId)
    {
        //todo: users bound to association only (with more associations mandatory!)
        $notIn = $this->getLeagueManagerOnDutyByUser($userId);
        $notIn[] = $userId;

        $qb = $this->getEntityManager()->createQueryBuilder('User');

        $qb->select('u')
            ->from('User\Entity\User', 'u')
            ->Where($qb->expr()->notIn('u.id', $notIn))
            ->andWhere('u.active = true');

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
            ->leftJoin('Support\Entity\Referee', 'r', Join::WITH, 'r.user = u')
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

}

