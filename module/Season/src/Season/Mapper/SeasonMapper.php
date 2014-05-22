<?php
namespace Season\Mapper;

use Nakade\Abstracts\AbstractMapper;
use Doctrine\ORM\Query\Expr\Join;
use Season\Entity\Title;

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

   public function getMyActualSeason()
   {
       $now = new \DateTime();
       $start = $now->modify('-2 week');

       $qb = $this->getEntityManager()->createQueryBuilder('Season');
       $qb->select('s')
           ->from('Season\Entity\Season', 's')
           ->leftJoin('League\Entity\League', 'l', Join::WITH, 'l._sid = s.id')
           ->leftJoin('League\Entity\Match', 'm', Join::WITH, 'l._id = m._lid')
           ->where('m._resultId is Null')
           ->andWhere('s.startDate < :start')
           ->setParameter('start', $start);

       $result = $qb->getQuery()->getOneOrNullResult();

       return $result;
   }

    //just one active season (by title)
    //actual => open matches
    //start date has passed => hasStarted
    public function getActualSeasonByTitle($titleId=1)
    {
        $now = new \DateTime();
        $start = $now->modify('-2 week');

        $qb = $this->getEntityManager()->createQueryBuilder('Season');
        $qb->select('s')
            ->from('Season\Entity\Season', 's')
            ->leftJoin('League\Entity\League', 'l', Join::WITH, 'l._sid = s.id')
            ->leftJoin('League\Entity\Match', 'm', Join::WITH, 'l._id = m._lid')
            ->where('m._resultId is Null')
            ->andWhere('s.title = :title')
            ->andWhere('s.startDate < :start')
            ->addOrderBy('s.startDate', 'DESC')
            ->setParameter('title', $titleId)
            ->setParameter('start', $start);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
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
