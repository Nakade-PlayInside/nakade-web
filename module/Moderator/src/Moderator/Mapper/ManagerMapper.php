<?php
namespace Moderator\Mapper;

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

}

