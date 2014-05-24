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
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('League\Entity\Match', 'm', Join::WITH, 'l.id = m._lid')
            ->where('m._resultId is not Null')
            ->andWhere('s.association = :association')
            ->addOrderBy('s.startDate', 'DESC')
            ->setMaxResults(1)
            ->setParameter('association', $associationId);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getMyLeague()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from('Season\Entity\League', 'l')
            ->leftJoin('Season\Entity\Season', 's', Join::WITH, 'l.season = s')
            ->where('l.id=1');

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
      try {

        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('min(m._date) as firstMatchDate,
            max(m._date) as lastMatchDate,
            count(m) as noMatches')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.season = s')
            ->leftJoin('League\Entity\Match', 'm', Join::WITH, 'l.id = m._lid')
            ->where('s.id = :seasonId')
            ->setParameter('seasonId', $seasonId);

        $data = $qb->getQuery()->getOneOrNullResult();
      } catch (\Exception $e) {
          echo $e->getMessage() . PHP_EOL;
      }

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
     * @return int
     */
    public function getNoOfOpenMatchesInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('League');
        $qb->select('count(m)')
            ->from('League\Entity\Match', 'm')
            ->leftJoin('Season\Entity\League', 'l', Join::WITH, 'l.id = m._lid')
            ->where('l.season = :seasonId')
            ->andWhere('m._resultId is Null')
            ->addGroupBy('l.id')
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
        $qb = $this->getEntityManager()->createQueryBuilder('League');
        $qb->select('count(l)')
            ->from('Season\Entity\League', 'l')
            ->join('l.season', 'Season')
            ->where('Season.id = :seasonId')
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
     * Needed for getMatchesOpenForAppointmentByUser
     *
     * @param User $user
     *
     * @return array
     */
    public function getLeagueIdsByPlayersInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Player');
        $result = $qb
            ->select('p.lid as league')
            ->from('League\Entity\Player', 'p')
            ->where('p.sid = :seasonId')
            ->addGroupBy('p.lid')
            ->setParameter('seasonId', $seasonId)
            ->getQuery()
            ->getResult();

        //quicker than array_map
        $ids = array();
        foreach ($result as $item) {
            $ids[] = $item['league'];
        }

        return $ids;
    }

    public function getLeaguesWithNoPlayersInSeason($seasonId)
    {

        $playersInLeagues = $this->getLeagueIdsByPlayersInSeason($seasonId);

        $qb = $this->getEntityManager()->createQueryBuilder('League');
        $qb->select('l')
            ->from('Season\Entity\League', 'l')
            ->join('l.season', 'Season')
            ->where('Season.id = :seasonId')
            ->andWhere($qb->expr()->notIn('l.id', $playersInLeagues))
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
    }

    /**
     * Needed for getMatchesOpenForAppointmentByUser
     *
     * @param User $user
     *
     * @return array
     */
    public function getLeagueIdsInSeason($seasonId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('Match');
        $result = $qb
            ->select('l._lid as league')
            ->from('League\Entity\League', 'l')
            ->where('l._sid = :seasonId')
            ->addGroupBy('m._lid')
            ->setParameter('seasonId', $seasonId)
            ->getQuery()
            ->getResult();

        //quicker than array_map
        $ids = array();
        foreach ($result as $item) {
            $ids[] = $item['league'];
        }

        return $ids;
    }

    public function getLeaguesWithNoMatchesInSeason($seasonId)
    {

        $matchesInLeagues = $this->getLeagueIdsInSeason($seasonId);

        $qb = $this->getEntityManager()->createQueryBuilder('Match');
        $qb->select('l')
            ->from('League\Entity\Match', 'l')
            ->where('l._sid = :seasonId')
            ->andWhere($qb->expr()->notIn('l._id', $matchesInLeagues))
            ->setParameter('seasonId', $seasonId);

        return $qb->getQuery()->getResult();
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
