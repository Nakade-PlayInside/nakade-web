<?php
namespace League\Controller\Plugin;

use League\Entity\Season;

/**
 * PlugIn for managing league database requests.
 */
class LeaguePlugin extends AbstractEntityPlugin
{

    /**
     * @param Season $season
     *
     * @return object
     */
    public function getTopLeague(Season $season)
    {
       return $this->getLeague($season, 1);
    }

    /**
     * @param Season $season
     * @param int $number
     *
     * @return object
     */
    public function getLeague(Season $season, $number)
    {
       return $this->getEntityManager()
                   ->getRepository('League\Entity\League')
                   ->findOneBy(
                       array(
                           '_sid'   => $season->getId(),
                           '_number' => $number,
                       )
                   );
    }

    /**
     * @param Season $season
     *
     * @return array
     */
    public function getAllLeaguesInSeason(Season $season)
    {
        return $this->getEntityManager()
                    ->getRepository('League\Entity\League')
                    ->findBy(
                        array('_sid' => $season->getId()),
                        array('_number' => 'ASC')
                    );
    }

    /**
     * @param Season $season
     *
     * @return int
     */
    public function getNoLeaguesInSeason(Season $season)
    {
        $league = $this->getAllLeaguesInSeason($season);
        return count($league);
    }

    /**
     * @param int $number
     *
     * @return mixed
     */
    public function getLastLeagueByOrder($number)
    {

      $dql = "SELECT l as last FROM League\Entity\League l
              WHERE l._number=:number
              ORDER BY l._id DESC";

      $result = $this->getEntityManager()
                     ->createQuery($dql)
                     ->setParameter('number', $number)
                     ->setMaxResults(1)
                     ->getOneOrNullResult();

      return $result['last'];

    }

    /**
     * @param Season $season
     *
     * @return array
     */
    public function getFormLeagues(Season $season)
    {
        $leagues  = $this->getAllLeaguesInSeason($season);

        $league =array();
        foreach ($leagues as $res) {

            //text
            $title = $res->getTitle();
            if ($res->getTitle()==null) {
                $title = $this->getController()
                              ->translate('League No. ') . $res->getNumber();
            }

            $league[$res->getId()] = $title;
        }
        return $league;
    }
}

?>
