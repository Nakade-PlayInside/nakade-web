<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;
use Season\Entity\Match;

/**
 * Class ScheduleMapper
 *
 * @package League\Mapper
 */
class ScheduleMapper  extends AbstractMapper
{

    /**
     * @param int $seasonId
     * @param int $userId
     *
     * @return mixed
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

    /**
     * open matches in an 24h time slot
     *
     * @param int $time
     *
     * @return array
     */
    public function getNextMatchesByTimeSlot($time=24)
    {
        $now = new \DateTime();
        $before = clone $now;
        $before->modify('+' . $time . ' hour');

        $timeSlot = clone $before;
        $timeSlot->modify('+24 hour');

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->where('m.result IS NULL')
            ->andWhere('m.date BETWEEN :before AND :slot')
            ->setParameter('before', $before)
            ->setParameter('slot', $timeSlot)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $leagueId
     *
     * @return null|\Season\Entity\Season
     */
    public function getSeasonRulesByLeague($leagueId)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Time')
            ->select('s')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->where('l.id = :leagueId')
            ->setParameter('leagueId', $leagueId);

        return $qb->getQuery()->getOneOrNullResult();

    }

}

