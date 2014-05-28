<?php
namespace Season\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;
/**
 * Description of PlayerMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class ParticipantMapper extends AbstractMapper
{

    public function getAllPlayersInLeague($leagueId)
    {
         return $this->getEntityManager()
                     ->getRepository('League\Entity\Participants')
                     ->findBy(array('_lid' => $leagueId));
    }


    /**
    * Getting the number of players in a season
    *
    * @param int $seasonId
    * @return int
    */
    public function getPlayerNumberInSeason($seasonId)
    {
       $dql = "SELECT count(p) as number FROM
               League\Entity\Participants p
               WHERE p._sid = :sid";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getSingleScalarResult();

    }

    /**
    * Get free players for a season. A free player is not
    * already participating in that season.
    *
    * @param int $seasonId
    * @return array
    */
    public function getFreePlayersForSeason($seasonId)
    {

       $dql = "SELECT u FROM
               User\Entity\User u
               WHERE u.id NOT IN (SELECT p._uid FROM
               League\Entity\Participants p
               WHERE p._sid = :sid)";

       $players = $this->getEntityManager()
                       ->createQuery($dql)
                       ->setParameter('sid', $seasonId)
                       ->getResult();

       return $players;

    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getParticipantsBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->innerJoin('p.season', 'MySeason')
            ->where('MySeason.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getInvitedPlayerIdsBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u.id')
            ->from('User\Entity\User', 'u')
            ->leftJoin('Season\Entity\Participant', 'p', Join::WITH, 'u = p.user')
            ->innerJoin('p.season', 'Season')
            ->where('Season.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        $result = $qb->getQuery()->getResult();

        //quicker than array_map
        $ids = array();
        foreach ($result as $item) {
            $ids[] = $item['id'];
        }

        return $ids;
    }


    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAvailablePlayersBySeason($seasonId)
    {
        $notIn = $this->getInvitedPlayerIdsBySeason($seasonId);

        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('u')
            ->from('User\Entity\User', 'u')
            ->where($qb->expr()->notIn('u.id', $notIn))
            ->andWhere('u.active = true')
            ->andWhere('u.verified = true')
            ->orderBy('u.firstname', 'ASC')
            ->addOrderBy('u.lastname', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
