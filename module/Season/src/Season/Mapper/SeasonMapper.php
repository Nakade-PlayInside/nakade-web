<?php
namespace Season\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use Season\Entity\Season;
use Season\Entity\Title;
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
   public function getSeasonById($id)
   {
       return $this->getEntityManager()
           ->getRepository('Season\Entity\Season')
           ->find($id);
   }

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
     * get all seasons of a titled league
     *
     * @param int $associationId
     *
     * @return null|Season
     */
    public function getSeasonsByAssociation($associationId=1)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->where('s.association = :association')
            ->addOrderBy('s.startDate', 'DESC')
            ->setParameter('association', $associationId);

        return $qb->getQuery()->getResult();
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
            ->leftJoin('League\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('League\Entity\Match', 'm', Join::WITH, 'l.id = m._lid')
            ->where('m._resultId is not Null')
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
        $qb->select('min(m._date) as firstMatchDate,
            max(m._date) as lastMatchDate,
            count(m) as noMatches')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('League\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('League\Entity\Match', 'm', Join::WITH, 'l.id = m._lid')
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
        $qb->select('max(m._date) as lastMatchDate')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('League\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('League\Entity\Match', 'm', Join::WITH, 'l.id = m._lid')
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
        $qb = $this->getEntityManager()->createQueryBuilder('League');
        $qb->select('count(m) as open')
            ->from('League\Entity\Match', 'm')
            ->leftJoin('League\Entity\League', 'l', Join::WITH, 'l.id = m._lid')
            ->where('l.sid = :seasonId')
            ->andWhere('m._resultId is Null')
            ->addGroupBy('l.id')
            ->setParameter('seasonId', $seasonId);
        $result = $qb->getQuery()->getOneOrNullResult();

        if (empty($result)) {
            return $result;
        }
        return intval($result['open']);
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
            ->from('League\Entity\League', 'l')
            ->where('l.sid = :seasonId')
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
            ->from('League\Entity\Player', 'p')
            ->where('p.sid = :seasonId')
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
   * Actual Season has one or more leagues, players and a match schedule.
   * The latter is the major property of a season. A season is active
   * if matches are open to be played.
   * There can be only one active season.
   *
   * @return /League/Entity/Season $season
   */
   public function getActualSeason()
   {

      $dql = "SELECT s as actual FROM League\Entity\Season s,
              League\Entity\League l,
              League\Entity\Match m
              WHERE s._id=l._sid AND
              l._id=m._lid AND
              m._resultId IS NULL";


      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->getOneOrNullResult();

      return $result['actual'];

   }

   /**
   * The last season is the max number of all seasons with all matches played.
   *
   * @return /League/Entity/Season $season
   */
   public function getLastSeason()
   {

      $dql = "SELECT s FROM League\Entity\Season s,
              League\Entity\League l,
              League\Entity\Match m
              WHERE s._id=l._sid AND
              l._id=m._lid
              ORDER BY s._number DESC";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setMaxResults(1)
                     ->getOneOrNullResult();

      return $result;

   }

   /**
   * A new season has no match schedule. The newest season is the one
   * with the highest number.
   *
   * @return /League/Entity/Season $season
   */
   public function getNewSeason()
   {

      $dql = "SELECT s FROM League\Entity\Season s
              WHERE s._id NOT IN ( SELECT t._id
              FROM League\Entity\Season t,
              League\Entity\League l,
              League\Entity\Match m
              WHERE t._id=l._sid AND
              l._id=m._lid )
              ORDER BY s._number DESC";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setMaxResults(1)
                     ->getOneOrNullResult();

      return $result;

   }

   /**
   * Getting the Season of a league.
   *
   * @return /League/Entity/Season $season
   */
   public function getSeasonByLeagueId($lid)
   {

      $dql = "SELECT s as season FROM League\Entity\Season s,
              League\Entity\League l
              WHERE l._id=:leagueId AND
              s._id=l._sid";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('leagueId', $lid)
                     ->getOneOrNullResult();

      return $result['season'];

   }



   /**
   * Getting the Season by the order number
   *
   * @return /League/Entity/Season $season
   */
   public function getSeasonByNumber($number)
   {

       $dql = "SELECT s FROM League\Entity\Season s
              WHERE s._number=:number";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('number', $number)
                     ->getOneOrNullResult();

      return $result;

   }


}

?>
