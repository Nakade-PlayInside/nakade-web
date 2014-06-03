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
class MatchMapper  extends AbstractMapper
{

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

    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getActualMatchesByUser($seasonId, $userId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('Match')
            ->select('m')
            ->from('Season\Entity\Match', 'm')
            ->innerJoin('m.white', 'White')
            ->innerJoin('m.black', 'Black')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->innerJoin('l.season', 'Season')
            ->where('(White.id = :uid OR Black.id = :uid)')
            ->andWhere('Season = :seasonId')
            ->setParameter('uid', $userId)
            ->setParameter('seasonId', $seasonId)
            ->orderBy('m.date', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    //-----------------------------------------------------------/
    /**
     * @param int $leagueId
     *
     * @return array
     */
    public function getMatchesInLeague($leagueId)
    {
       return $this->getEntityManager()
                   ->getRepository('Season\Entity\Match')
                   ->findBy(
                       array('lid' => $leagueId),
                       array('date'=> 'ASC')
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
            ->from('Season\Entity\Match', 'm')
            ->join('m.white', 'White')
            ->join('m.black', 'Black')
            ->join('m.league', 'League')
            ->Where('League.id = :lid')
            ->andWhere('(White.id = :uid OR Black.id = :uid)')
            ->setParameter('lid', $leagueId)
            ->setParameter('uid', $uid)
            ->orderBy('m.date', 'ASC');

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
     * get the match by id
     *
     * @param int $id
     *
     * @return \Season\Entity\Match
     */
    public function getMatchById($id)
    {

       return $this->getEntityManager()
                   ->getRepository('Season\Entity\Match')
                   ->find($id);

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
            ->select('max(m.date)')
            ->from('Season\Entity\Match', 'm')
            ->join('m.league', 'League')
            ->where('League.id = :lid')
            ->setParameter('lid', $leagueId);

        $endDate = $qb->getQuery()->getSingleScalarResult();
        return \DateTime::createFromFormat('Y-m-d H:i:s', $endDate);

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
            ->from('Season\Entity\Match', 'm')
            ->join('m.black', 'Black')
            ->join('m.white', 'White')
            ->join('m.league', 'League')
            ->Where('League.id = :lid')
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
            ->from('Season\Entity\Match', 'm')
            ->join('m.black', 'Black')
            ->join('m.white', 'White')
            ->where($qb->expr()->notIn('m.id', $shiftedMatches))
            ->andWhere('m.result is Null')
            ->andWhere('Black.id = :uid OR White.id = :uid')
            ->andWhere('m.date > :deadline')
            ->setParameter('uid', $user->getId())
            ->setParameter('deadline', $now)
            ->orderBy('m.date ', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}

