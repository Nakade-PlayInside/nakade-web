<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;

/**
 * Class ResultMapper
 *
 * @package League\Mapper
 */
class ResultMapper  extends AbstractMapper
{

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getActualOpenResultsBySeason($seasonId)
    {
        $now = new \DateTime();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->where('m.result IS NULL')
            ->andWhere('Season.id = :seasonId')
            ->andWhere('m.date < :now')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('now', $now)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAllOpenResultsBySeason($seasonId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->where('m.result IS NULL')
            ->andWhere('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $time
     *
     * @return array
     */
    public function getActualOpenResults($time=72)
    {
        $now = new \DateTime();
        $now->modify('-'.$time.' hour');

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->where('m.result IS NULL')
            ->andWhere('m.date < :now')
            ->setParameter('now', $now)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();

    }

    /**
     * @param int $seasonId
     * @param int $userId
     *
     * @return array
     */
    public function getResultsByUser($seasonId, $userId)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->innerJoin('m.black', 'Black')
            ->innerJoin('m.white', 'White')
            ->where('Season.id = :seasonId')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('uid', $userId)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     * @param int $userId
     *
     * @return array
     */
    public function getOpenResultsByUser($seasonId, $userId)
    {

        $now = new \DateTime();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->innerJoin('l.season', 'Season')
            ->innerJoin('m.black', 'Black')
            ->innerJoin('m.white', 'White')
            ->where('m.result IS NULL')
            ->andWhere('Season.id = :seasonId')
            ->andWhere('m.date < :now')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->setParameter('seasonId', $seasonId)
            ->setParameter('now', $now)
            ->setParameter('uid', $userId)
            ->orderBy('m.date', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $matchId
     *
     * @return object
     */
    public function getMatchById($matchId)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Match')
            ->find($matchId);

    }

    /**
     * @param int $leagueId
     * @param int $matchDay
     *
     * @return array
     */
    public function getMatchesByMatchDay($leagueId, $matchDay=1)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.matchDay', 'd')
            ->innerJoin('m.league', 'League')
            ->where('League.id = :leagueId')
            ->andWhere('d.matchDay = :matchDay')
            ->setParameter('leagueId', $leagueId)
            ->setParameter('matchDay', $matchDay)
            ->orderBy('m.result', 'ASC')
            ->addOrderBy('m.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $leagueId
     *
     * @return int
     */
    public function getActualMatchDayByLeague($leagueId)
    {

        $now = new \DateTime();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('max(d.matchDay)')
            ->distinct()
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.league', 'l')
            ->leftJoin('Season\Entity\MatchDay', 'd', JOIN::WITH, 'm.matchDay=d')
            ->where('l.id = :leagueId')
            ->andWhere('d.date < :now')
            ->setParameter('now', $now)
            ->setParameter('leagueId', $leagueId);

        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR));
    }

    /**
     * @param int $leagueId
     *
     * @return int
     */
    public function getNoOfMatchDaysByLeague($leagueId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('max(d.matchDay)')
            ->from('Season\Entity\MatchDay', 'd')
            ->leftJoin('Season\Entity\Match', 'm', JOIN::WITH, 'm.matchDay=d')
            ->innerJoin('m.league', 'l')
            ->where('l.id = :leagueId')
            ->setParameter('leagueId', $leagueId);

        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR));

    }

    /**
     * //todo: unused yet
     * @return array
     */
    public function getOverdueResults()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->where('m.result IS NULL')
            ->andWhere('m.date < :now')
            ->setParameter('now', new \DateTime());

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getResultReminderMatchId()
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder('Match')
            ->select('m.id')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('League\Entity\ResultReminder', 'r', JOIN::WITH, 'r.match=m' )
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
    public function getNewOverdueMatches()
    {
        $notIn = $this->getResultReminderMatchId();

        $qb = $this->getEntityManager()->createQueryBuilder('newMatches');
        $qb->select('m')
            ->from('Season\Entity\Match', 'm')
            ->where('m.result IS NULL')
            ->andWhere($qb->expr()->notIn('m.id', $notIn))
            ->andWhere('m.date < :now')
            ->setParameter('now', new \DateTime());

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getResultReminder()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('reminder')
            ->select('r')
            ->from('League\Entity\ResultReminder', 'r')
            ->where('r.nextDate < :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function getExpiredResultReminder()
    {
        return $this->getEntityManager()
            ->createQueryBuilder('reminder')
            ->select('r')
            ->from('League\Entity\ResultReminder', 'r')
            ->innerJoin('r.match', 'Match')
            ->where('Match.result IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

}

