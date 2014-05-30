<?php
namespace Season\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use \Doctrine\ORM\Query;

/**
 * Class LeagueMapper
 *
 * @package Season\Mapper
 */
class LeagueMapper extends AbstractMapper
{

    /**
     * @param int $seasonId
     *
     * @return int
     */
    public function getNewLeagueNoBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('League');
        $qb->select('max(l.number)')
            ->from('Season\Entity\League', 'l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->where('s.id = :seasonId')
            ->addOrderBy('l.number', 'DESC')
            ->setParameter('seasonId', $seasonId);

        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR)) +1;
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getAvailableParticipantsBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('p')
            ->from('Season\Entity\Participant', 'p')
            ->innerJoin('p.season', 'MySeason')
            ->where('MySeason.id = :seasonId')
            ->andWhere('p.league IS NULL')
            ->andWhere('p.hasAccepted = 1')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

}

