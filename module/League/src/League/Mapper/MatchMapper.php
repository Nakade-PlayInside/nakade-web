<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
use User\Entity\User;


/**
 * Description of MatchMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class MatchMapper  extends AbstractMapper
{

    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getMatchesInLeague($leagueId)
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\Match')
                   ->findBy(
                       array('_lid' => $leagueId),
                       array('_date'=> 'ASC')
                   );
    }

    /**
     * @param int $leagueId
     * @param int $uid
     *
     * @return array
     */
    public function getMyMatchesInLeague($leagueId, $uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('League\Entity\Match', 'm')
            ->Where('m._lid = :lid')
            ->andWhere('(m._whiteId = :uid OR m._blackId = :uid)')
            ->setParameter('lid', $leagueId)
            ->setParameter('uid', $uid)
            ->orderBy('m._date', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    //@todo: unfinished
    public function getOpenResultsByUser($leagueId, $uid)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('League\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->Where('m._lid = :leagueId')
            ->andWhere('(m._whiteId = :uid OR m._blackId = :uid)')
            ->setParameter('lid', $leagueId)
            ->setParameter('uid', $uid)
            ->orderBy('m._date', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
    /**
     * Get all open results of the season.
     * It may happen to be more than one league only
     *
     * @param int $seasonId
     *
     * @param int $uid
     *
     * @return array Match
     */
    public function getMyOpenResults($seasonId, $uid)
    {

       $dql = "SELECT m FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id AND
               (m._blackId = :uid OR m._whiteId = :uid) AND
               m._resultId IS NULL
               AND m._date < :today
               ORDER BY m._date ASC";

       $today = new \DateTime();
       $today->modify('-6 hours');

       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('today', $today)
                   ->setParameter('uid', $uid)
                   ->setParameter('sid', $seasonId)
                   ->getResult();
    }

    /**
     * @param int $seasonId
     * @param int $uid
     *
     * @return array
     */
    public function getMyResults($seasonId, $uid)
    {

       $dql = "SELECT m FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id AND
               (m._blackId = :uid OR m._whiteId = :uid)
               ORDER BY m._date ASC";

       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('uid', $uid)
                   ->setParameter('sid', $seasonId)
                   ->getResult();
    }


     /**
     * Get all open results of the season.
     * It may happen to be more than one league only
     *
     * @param int $seasonId
     * @return array objects Match
     */
    public function getAllOpenResults($seasonId)
    {

       $dql = "SELECT m FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id AND
               m._resultId IS NULL
               AND m._date < :today
               ORDER BY m._date ASC";

       $today = new \DateTime();
       $today->modify('-6 hours');

       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('today', $today)
                   ->setParameter('sid', $seasonId)
                   ->getResult();
    }

    /**
     * get number of open matches in a season
     *
     * @param int $seasonId
     * @return int
     */
    public function getOpenMatches($seasonId)
    {

        $dql = "SELECT count(m) as number FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id AND
               m._resultId IS NULL";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getSingleScalarResult();

    }

    /**
     * get number of matches in a season
     *
     * @param int $seasonId
     * @return int
     */
    public function getNumberOfMatches($seasonId)
    {

        $dql = "SELECT count(m) as number FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getSingleScalarResult();

    }

    /**
     * get the match by id
     *
     * @param int $id
     * @return \League\Entity\Match
     */
    public function getMatchById($id)
    {

       return $this->getEntityManager()
                   ->getRepository('League\Entity\Match')
                   ->find($id);

    }

    /**
     * get all matches with a result in a league
     *
     * @param int $leagueId
     *
     * @return array League\Entity\Match
     */
    public function getAllMatchesWithResult($leagueId)
    {

       $dql = "SELECT m FROM
               League\Entity\Match m
               WHERE m._lid = :lid AND
               m._resultId IS NOT NULL";

       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('lid', $leagueId)
                   ->getResult();

    }

    /**
     * get all matches with a result in a league
     *
     * @param int $seasonId
     *
     * @return array League\Entity\Match
     */
    public function getAllOpenMatches($seasonId)
    {

       $dql = "SELECT m FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id AND
               m._resultId IS NULL";

       return $this->getEntityManager()
                   ->createQuery($dql)
                   ->setParameter('sid', $seasonId)
                   ->getResult();

    }


    /**
     * @param int $leagueId
     *
     * @return \DateTime
     */
    public function getEndOfLeague($leagueId)
    {

        //not used
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('max(m._date)')
            ->from('League\Entity\Match', 'm')
            ->join('m._league', 'League')
            ->where('League._id = :lid')
            ->setParameter('lid', $leagueId);

        $endDate = $qb->getQuery()->getSingleScalarResult();
        return \DateTime::createFromFormat('Y-m-d H:i:s', $endDate);

    }

    public function getLastMatchDate($seasonId)
    {

        $dql = "SELECT max(m._date) as datum FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getSingleScalarResult();

    }

    public function getFirstMatchDate($seasonId)
    {

        $dql = "SELECT min(m._date) as datum FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l._sid = :sid AND
               m._lid=l._id";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getSingleScalarResult();

    }

    public function getLeagueInSeasonByPlayer($seasonId, $userId)
    {
        $dql = "SELECT l FROM
               League\Entity\Match m,
               League\Entity\League l
               WHERE l.sid = :sid AND
               m._lid=l.id   AND
               (m._blackId = :uid OR
               m._whiteId = :uid)";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->setParameter('uid', $userId)
                    ->setMaxResults(1)
                    ->getOneOrNullResult();

    }

    /**
     * @param \User\Entity\User $user
     * @param int               $lid
     *
     * @return array
     */
    public function getNextMatchDatesInLeagueByUser($user, $lid)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m._date')
            ->from('League\Entity\Match', 'm')
            ->join('m._black', 'Black')
            ->join('m._white', 'White')
            ->Where('m._lid = :lid')
            ->andWhere('Black.id = :uid OR White.id = :uid')
           // ->andWhere('m._date > :now')
            ->setParameter('uid', $user->getId())
            ->setParameter('lid', $lid)
           // ->setParameter('now', new \DateTime())
            ->orderBy('m._date ', 'ASC');

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param User  $user
     * @param array $shiftedMatches
     * @param int   $timeLimit
     *
     * @return array
     */
    public function getMatchesOpenForAppointmentByUser(User $user, array $shiftedMatches, $timeLimit=72)
    {
        //mandatory array is never empty
        if (empty($shiftedMatches)) {
            $shiftedMatches[]=0;
        }

        $now = new \DateTime();
        $now->modify('+'.$timeLimit.' hour');

        $qb = $this->getEntityManager()->createQueryBuilder('Match');
        $qb->select('m')
            ->from('League\Entity\Match', 'm')
            ->join('m._black', 'Black')
            ->join('m._white', 'White')
            ->where($qb->expr()->notIn('m._id', $shiftedMatches))
            ->andWhere('m._resultId is Null')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->andWhere('m._date > :deadline')
            ->setParameter('uid', $user->getId())
            ->setParameter('deadline', $now)
            ->orderBy('m._date ', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}

