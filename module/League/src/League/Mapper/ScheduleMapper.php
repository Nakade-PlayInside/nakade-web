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
    public function getNextMatchesInTime($time=24)
    {
        $before = new \DateTime();
        $before->modify('+' . $time . ' hour');
        $notIn = $this->getMatchReminderMatchId();

        $qb = $this->getEntityManager()->createQueryBuilder('matchReminder');
        $qb->select('m')
            ->from('Season\Entity\Match', 'm')
            ->where('m.result IS NULL')
            ->andWhere($qb->expr()->notIn('m.id', $notIn))
            ->andWhere('m.date < :before')
            ->setParameter('before', $before);

        return $qb->getQuery()->getResult();

    }

    /**
     * @return array
     */
    public function getMatchReminderMatchId()
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('m.id')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('League\Entity\MatchReminder', 'r', JOIN::WITH, 'r.match=m' )
            ->innerJoin('r.match', 'Match')
            ->getQuery()
            ->getResult();

        //mandatory array is never empty
        $idArray = array(0 => 0);
        foreach ($result as $item) {
            $idArray[] = $item['id'];
        }

        return $idArray;
    }

    /**
     * @return array
     */
    public function getExpiredMatchReminder()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('reminder')
            ->select('r')
            ->from('League\Entity\MatchReminder', 'r')
            ->innerJoin('r.match', 'Match')
            ->where('Match.result IS NOT NULL')
            ->getQuery()
            ->getResult();
    }


    /**
     * @param int $days
     *
     * @return array
     */
    public function getMatchesForAppointmentReminder($days=24)
    {
        $before = new \DateTime();
        $before->modify('+' . $days . ' day');
        $notIn = $this->getAppointmentReminderMatchId();

        $qb = $this->getEntityManager()->createQueryBuilder('appointmentReminder');
        $qb->select('m')
            ->from('Season\Entity\Match', 'm')
            ->where('m.result IS NULL')
            ->andWhere($qb->expr()->notIn('m.id', $notIn))
            ->andWhere('m.date < :before')
            ->setParameter('before', $before);

        return $qb->getQuery()->getResult();

    }

    /**
     * @return array
     */
    public function getAppointmentReminderMatchId()
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('m.id')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('League\Entity\AppointmentReminder', 'r', JOIN::WITH, 'r.match=m' )
            ->innerJoin('r.match', 'Match')
            ->getQuery()
            ->getResult();

        //mandatory array is never empty
        $idArray = array(0 => 0);
        foreach ($result as $item) {
            $idArray[] = $item['id'];
        }

        return $idArray;
    }

    /**
     * @return array
     */
    public function getExpiredAppointmentReminder()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('reminder')
            ->select('r')
            ->from('League\Entity\AppointmentReminder', 'r')
            ->innerJoin('r.match', 'Match')
            ->where('Match.result IS NOT NULL')
            ->getQuery()
            ->getResult();
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

