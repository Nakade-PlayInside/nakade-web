<?php
namespace Season\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use Season\Entity\Season;
use \Doctrine\ORM\Query;

/**
 * Class SeasonMapper
 *
 * @package Season\Mapper
 */
class SeasonMapper extends AbstractMapper
{
     /**
     * @param int $id
     *
     * @return object
     */
    public function getAssociationById($id)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Association')
            ->find($id);
    }

    /**
     * @param int $matchId
     *
     * @return \Season\Entity\MatchDay
     */
    public function getMatchDayById($matchId)
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\MatchDay')
            ->find($matchId);
    }

    /**
     * active season has already started and the isReady flag is set
     *
     * @param int $associationId
     *
     * @return null|Season
     */
    public function getActiveSeasonByAssociation($associationId=1)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->where('s.isReady = 1')
            ->andWhere('s.association = :association')
            ->andWhere('s.startDate < :start')
            ->addOrderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('association', $associationId)
            ->setParameter('start', new \DateTime());

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * new season has not yet started
     *
     * @param int $associationId
     *
     * @return null|Season
     */
    public function getNewSeasonByAssociation($associationId=1)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->where('s.association = :association')
            ->andWhere('s.startDate > :start')
            ->addOrderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('association', $associationId)
            ->setParameter('start', new \DateTime());

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $associationId
     *
     * @return bool
     */
    public function hasNewSeasonByAssociation($associationId=1)
    {
        return !is_null($this->getNewSeasonByAssociation($associationId));
    }

    /**
     * last season has no open matches. It's the last season played!
     *
     * @param int $associationId
     *
     * @return null|Season
     */
    public function getLastSeasonByAssociation($associationId=1)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('Season\Entity\Match', 'm', Join::WITH, 'l = m.league')
            ->where('m.result IS NOT Null')
            ->andWhere('s.association = :association')
            ->addOrderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('association', $associationId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * returns a mapped array of season info data
     *
     * @param int $seasonId
     *
     * @return null|array
     */
    public function getSeasonInfo($seasonId)
    {
      $data = null;
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('min(m.date) as firstMatchDate,
            max(m.date) as lastMatchDate,
            count(m) as noMatches')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('Season\Entity\Match', 'm', Join::WITH, 'l = m.league')
            ->where('s.id = :seasonId')
            ->setParameter('seasonId', $seasonId);
        $data = $qb->getQuery()->getOneOrNullResult();

        if (!empty($data)) {
            $data['openMatches'] = $this->getNoOfOpenMatchesInSeason($seasonId);
            $data['noLeagues'] = $this->getNoOfLeaguesInSeason($seasonId);
            $data['noPlayers'] = $this->getNoOfPlayersInSeason($seasonId);
        }

        return $data;
    }

    /**
     * @param int $seasonId
     *
     * @return \DateTime
     *
     * @throws \RuntimeException
     */
    public function getLastMatchDateOfSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('max(m.date) as lastMatchDate')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('Season\Entity\Match', 'm', Join::WITH, 'l = m.league')
            ->where('s.id = :id')
            ->setParameter('id', $seasonId);
        $result = $qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR);
        if (is_null($result)) {
            throw new \RuntimeException(
                sprintf('No match date found! Check season with id=%s.', $seasonId)
            );
        }
        return \DateTime::createFromFormat('Y-m-d H:i:s', $result);
    }

    /**
     * @param int $seasonId
     *
     * @return int
     */
    public function getNoOfOpenMatchesInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Match');
        $qb->select('count(m)')
            ->from('Season\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'm.league = l')
            ->innerJoin('l.season', 'mySeason')
            ->where('mySeason.id = :seasonId')
            ->andWhere('m.result IS NULL')
            ->setParameter('seasonId', $seasonId);
        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR));

    }

    /**
     * @param int $seasonId
     *
     * @return int
     */
    public function getNoOfLeaguesInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(l)')
            ->from('Season\Entity\League', 'l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->where('s.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR));
    }

    /**
     * @param int $seasonId
     *
     * @return int
     */
    public function getNoOfPlayersInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Player');
        $qb->select('count(p)')
            ->from('Season\Entity\Participant', 'p')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'p.season = s')
            ->where('s.id = :seasonId')
            ->andWhere('p.league IS NOT NULL')
            ->setParameter('seasonId', $seasonId);

        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR));
    }

    /**
     * @return array
     */
    public function getTieBreaker()
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\TieBreaker')
            ->findAll();
    }

    /**
     * @return array
     */
    public function getByoyomi()
    {
        return $this->getEntityManager()
            ->getRepository('Season\Entity\Byoyomi')
            ->findAll();
    }

    /**
     * @param int $seasonId
     *
     * @return int
     *
     * //todo: this shows the max no of players per league. is this the no of matchDays?
     */
    public function getNoOfMatchDaysBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Participants');
        $qb->select('count(p) as no')
            ->from('Season\Entity\Participant', 'p')
            ->where('p.season = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->andWhere('p.league IS NOT NULL')
            ->andWhere('p.hasAccepted  = 1')
            ->groupBy('p.league')
            ->orderBy('no', 'DESC')
            ->setMaxResults(1);

        return intval($qb->getQuery()->getResult(Query::HYDRATE_SINGLE_SCALAR));
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function getMatchDaysBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('MatchDay');
        $qb->select('m')
            ->from('Season\Entity\MatchDay', 'm')
            ->where('m.season = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->orderBy('m.matchDay', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    public function removeMatchDaysBySeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('MatchDay');
        $qb->delete('m')
            ->from('Season\Entity\MatchDay', 'm')
            ->where('m.season = :seasonId')
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->execute();
    }

}
