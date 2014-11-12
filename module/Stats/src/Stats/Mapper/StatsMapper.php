<?php
namespace Stats\Mapper;

use Nakade\Abstracts\AbstractMapper;
use \Doctrine\ORM\Query;

/**
 * Class StatsMapper
 *
 * @package Stats\Mapper
 */
class StatsMapper extends AbstractMapper
{
    /**
     * @param int $uid
     *
     * @return array
     */
    public function getMatchStatsByUser($uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.black', 'Black')
            ->innerJoin('m.white', 'White')
            ->where('m.result IS NOT NULL')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->setParameter('uid', $uid)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();
    }



}
