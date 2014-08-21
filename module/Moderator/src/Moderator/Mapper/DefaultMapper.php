<?php
namespace Moderator\Mapper;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Moderator\Controller\SupportTypeInterface;
use Nakade\Abstracts\AbstractMapper;
use \Permission\Entity\RoleInterface;

/**
 * Class DefaultMapper
 *
 * @package Moderator\Mapper
 */
class DefaultMapper extends AbstractMapper implements RoleInterface, SupportTypeInterface
{

    /**
     * notIn method
     *
     * @param int $userId
     *
     * @return array
     */
    protected function getIdsOfAssociationsByOwner($userId)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Association')
            ->select('a.id')
            ->from('Season\Entity\Association', 'a')
            ->innerJoin('a.owner', 'Owner')
            ->where('Owner.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        return $this->getIdArray($result);
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
            ->andWhere('u.id = :uid')
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
            ->setParameter('userId', $userId);

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
            ->setParameter('userId', $userId);

        $this->addRefereeRoles($qb);
        $result = $qb->getQuery()->getResult();

        return !empty($result);
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    protected function addManagerRoles(QueryBuilder &$queryBuilder)
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
    protected function addAdminRoles(QueryBuilder &$queryBuilder)
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
    protected function addOwnerRoles(QueryBuilder &$queryBuilder)
    {
        $alias = current($queryBuilder->getDQLPart('from'))->getAlias();
        $queryBuilder
            ->andWhere("$alias.role != :guest")
            ->setParameter('guest', self::ROLE_GUEST);

    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    protected function addRefereeRoles(QueryBuilder &$queryBuilder)
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
    protected function getIdArray(array $result) {

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

