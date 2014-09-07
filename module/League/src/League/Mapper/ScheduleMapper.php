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
        $notIn = $this->getMatchIdByMatchReminder();

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
    public function getMatchIdByMatchReminder()
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('Match.id')
            ->from('League\Entity\MatchReminder', 'r')
            ->innerJoin('r.match', 'Match')
            ->getQuery()
            ->getResult();

        return $this->getIdArray($result);
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

        //matches with no appointment and no reminder
        $mid_1 = $this->getMatchIdByAppointmentReminder();
        $mid_2 = $this->getMatchIdByAppointment();
        $merge = array_merge($mid_1, $mid_2);
        $notIn = array_unique($merge);

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
    public function getMatchIdByAppointment()
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('Match.id')
            ->from('Appointment\Entity\Appointment', 'a')
            ->innerJoin('a.match', 'Match')
            ->getQuery()
            ->getResult();

        return $this->getIdArray($result);
    }

    /**
     * @return array
     */
    public function getMatchIdByAppointmentReminder()
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('Match.id')
            ->from('League\Entity\AppointmentReminder', 'a')
            ->innerJoin('a.match', 'Match')
            ->getQuery()
            ->getResult();

        return $this->getIdArray($result);
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

    /**
     * @param $uid
     *
     * @return null|\DateTime
     */
    public function getNextMatchDateByUser($uid)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('MIN(m.date)')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.white', 'White')
            ->innerJoin('m.black', 'Black')
            ->where('(White.id = :uid OR Black.id = :uid)')
            ->andWhere('m.result IS NULL')
            ->andWhere('m.date > :now')
            ->setParameter('uid', $uid)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();

        $minDate = array_shift($result[0]);
        if(!empty($minDate)) {

            try {

                $date = new \DateTime($minDate);
            } catch (\Exception $e) {
                return null;
            }
            return $date;
        }

        return null;

    }

    /**
     * @param $uid
     *
     * @return array
     */
    public function getOpenResultByUser($uid)
    {
        $limit = new \DateTime();
        $limit->modify('+3 day');

        return $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.white', 'White')
            ->innerJoin('m.black', 'Black')
            ->where('(White.id = :uid OR Black.id = :uid)')
            ->andWhere('m.result IS NULL')
            ->andWhere('m.date > :now AND m.date < :limit')
            ->setParameter('uid', $uid)
            ->setParameter('now', new \DateTime())
            ->setParameter('limit', $limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $result
     *
     * @return array
     */
    private function getIdArray(array $result) {

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

