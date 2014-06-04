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

    /**
     * @param int $seasonId
     *
     * @return mixed
     */
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


}

