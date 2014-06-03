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

}

