<?php
namespace League\Mapper;

use Nakade\Abstracts\AbstractMapper;
/**
 * Description of LeagueMapper
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class LeagueMapper extends AbstractMapper
{

    public function getTopLeagueBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('League');
        $qb->select('l')
            ->from('Season\Entity\League', 'l')
            ->innerJoin('l.season', 'season')
            ->andWhere('season.id = :seasonId')
            ->orderBy('l.number', 'DESC')
            ->setMaxResults(1)
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getOneOrNullResult();
    }

   /**
     * Getting the LeagueId
     *
     * @param int $seasonId
     * @param int $number league number
     * @return /League/Entity/League $league
     */
    public function getLeague($seasonId, $number)
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\League')
                   ->findOneBy(
                        array(
                           'sid'   => $seasonId,
                           'number' => $number,
                        )
                     );
    }

    /**
     * Getting the League by Id
     *
     * @param int $leagueId
     * @return /League/Entity/League $league
     */
    public function getLeagueById($leagueId)
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\League')
                   ->find($leagueId);
    }

    /**
    * Getting the number of league in a season
    *
    * @param int $seasonId
    * @return int
    */
    public function getLeaguesWithPlayers($seasonId)
    {

       $dql = "SELECT count(l) as number FROM
               League\Entity\League l,
               League\Entity\Participants p
               WHERE l._sid = :sid AND
               p._lid = l._id";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getSingleScalarResult();

    }

    /**
    * Getting the number of league in a season
    *
    * @param int $seasonId
    * @return int
    */
    public function getLeagueNumberInSeason($seasonId)
    {
       $dql = "SELECT count(l) as number FROM
               League\Entity\League l
               WHERE l._sid = :sid";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getSingleScalarResult();

    }

    /**
    * Get all leagues of a season
    *
    * @param int $seasonId
    * @return int
    */
    public function getLeaguesInSeason($seasonId)
    {
       $dql = "SELECT l FROM
               League\Entity\League l
               WHERE l._sid = :sid";

        return $this->getEntityManager()
                    ->createQuery($dql)
                    ->setParameter('sid', $seasonId)
                    ->getResult();

    }
}

?>
