<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;

/**
 * Class LeagueMapper
 *
 * @package League\Mapper
 */
class LeagueMapper extends AbstractMapper
{

    /**
     * @param int $seasonId
     *
     * @return mixed
     */
    public function getTopLeagueBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('League');
        $qb->select('l')
            ->from('Season\Entity\League', 'l')
            ->innerJoin('l.season', 'season')
            ->andWhere('season.id = :seasonId')
            ->orderBy('l.number', 'DESC')
            ->setMaxResults(1)
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getMatchesByLeague($leagueId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->innerJoin('m.league', 'League')
            ->where('League.id = :leagueId')
            ->setParameter('leagueId', $leagueId)
            ->orderBy('m.date', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}

