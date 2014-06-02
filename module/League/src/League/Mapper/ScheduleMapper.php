<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
use User\Entity\User;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;


/**
 * Description of MatchMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class ScheduleMapper  extends AbstractMapper
{

    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getLeagueByUser($seasonId, $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('League')
            ->select('l')
            ->from('Season\Entity\League', 'l')
            ->leftJoin('Season\Entity\Participant', 'p', Join::WITH, 'p.league = l')
            ->innerJoin('p.user', 'User')
            ->innerJoin('p.season', 'Season')
            ->where('User.id = :userId')
            ->andWhere('Season.id = :seasonId')
            ->setParameter('userId', $userId)
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getScheduleByLeague($leagueId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.league', 'League')
            ->where('(League.id = :leagueId)')
            ->setParameter('leagueId', $leagueId)
            ->orderBy('m.date', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $leagueId
     * @param int $uid
     *
     * @return array
     */
    public function getMyScheduleByUser($leagueId, $uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.league', 'League')
            ->innerJoin('m.white', 'White')
            ->innerJoin('m.black', 'Black')
            ->where('(League.id = :leagueId)')
            ->andWhere('(White.id = :uid OR Black.id = :uid)')
            ->setParameter('leagueId', $leagueId)
            ->setParameter('uid', $uid)
            ->orderBy('m.date', 'ASC');

        return $qb->getQuery()->getResult();

    }

}

