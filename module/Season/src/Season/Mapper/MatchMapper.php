<?php
namespace Season\Mapper;

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


}

