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
     * @return \Season\Entity\League|null
     */
    public function getTopLeagueBySeason($seasonId)
    {
        return $this->getLeagueByNumber($seasonId);
    }

    /**
     * @param int $leagueId
     *
     * @return \Season\Entity\League
     */
    public function getLeagueById($leagueId)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\League')
            ->find($leagueId);
    }

    /**
     * @param int $seasonId
     * @param int $number
     *
     * @return \Season\Entity\League|null
     */
    public function getLeagueByNumber($seasonId, $number=1)
    {
        return  $this->getEntityManager()
            ->createQueryBuilder('League')
            ->select('l')
            ->from('Season\Entity\League', 'l')
            ->innerJoin('l.season', 'season')
            ->where('season.id = :seasonId')
            ->andWhere('l.number = :number')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('number', $number)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getMatchesByLeague($leagueId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('myMatch')
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

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getLeaguesBySeason($seasonId)
    {
        return $this->getEntityManager()
            ->createQueryBuilder('league')
            ->select('l')
            ->from('Season\Entity\League', 'l')
            ->innerJoin('l.season', 'Season')
            ->where('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->getQuery()
            ->getResult();

    }


}

