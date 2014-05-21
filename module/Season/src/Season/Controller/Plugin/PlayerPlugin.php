<?php
namespace Season\Controller\Plugin;

use Season\Entity\Season;

/**
 * PlugIn for managing participants database requests.
 */
class PlayerPlugin extends AbstractEntityPlugin
{
    /**
     * get number of players in season
     *
     * @param \League\Entity\Season $season
     *
     * @return int
     */
    public function getNoPlayersInSeason(Season $season)
    {

       $dql="SELECT p FROM League\Entity\Participants p,
             League\Entity\League l
             WHERE p._lid=l._id
             AND p._sid=:sid" ;

       $players = $this->getEntityManager()
                       ->createQuery($dql)
                       ->setParameter('sid', $season->getId())
                       ->getResult();

       return count($players);
    }

    /**
     * get all available players for to this season.
     * That means they are not already added to a league.
     *
     * @param \League\Entity\Season $season
     *
     * @return int
     */
    public function getAllPlayers(Season $season)
    {

       $dql="SELECT u FROM User\Entity\User u
             WHERE u._id NOT IN (
             SELECT p._uid FROM League\Entity\Participants p
             WHERE p._sid=:sid)
             ORDER BY u._lastname ASC";

       $players = $this->getEntityManager()
                       ->createQuery($dql)
                       ->setParameter('sid', $season->getId())
                       ->getResult();

       return $players;
    }

    /**
     * get all players as array, id as key and fullname as value.
     * Only available players are listed.
     *
     * @param \League\Entity\Season $season
     *
     * @return array
     */
    public function getFormPlayers(Season $season)
    {

        $allPlayers  = $this->getAllPlayers($season);

        $player =array();
        foreach ($allPlayers as $res) {
            $player[$res->getId()] =
            $res->getLastname() .", ". $res->getFirstname();
        }

        return $player;
    }

}

?>
