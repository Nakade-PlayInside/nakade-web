<?php
namespace Stats\Mapper;

use Nakade\Abstracts\AbstractMapper;
use \Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;

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


    /**
     * @param int $uid
     *
     * @return array
     */
    public function getTournamentsByUser($uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Tournament')
            ->select('l')
            ->from('Season\Entity\League', 'l')
            ->innerJoin('Season\Entity\Participant', 'p', Join::WITH, 'p.league = l')
            ->innerJoin('Season\Entity\Match', 'm', Join::WITH, 'm.league = l')
            ->where('p.user = :uid')
            ->andWhere('p.hasAccepted = 1')
            ->andWhere('m.result IS NOT NULL')
            ->setParameter('uid', $uid);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $lid
     *
     * @return array
     */
    public function getMatchesByTournament($lid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('myMatch')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->innerJoin('m.league', 'League')
            ->where('League.id = :leagueId')
            ->setParameter('leagueId', $lid)
            ->orderBy('m.date', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}
