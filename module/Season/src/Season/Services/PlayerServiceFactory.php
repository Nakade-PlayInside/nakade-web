<?php
//module/Authentication/src/Authentication/Services/AuthServiceFactory.php
namespace Season\Services;

use Nakade\Abstracts\AbstractService;
use League\Entity\Player;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;


/**
 * Factory for the actual season for receiving
 * sorted league tables and schedules.
 *
 * @author Dr. Holger Maerz <grrompf@gmail.com>
 */
class PlayerServiceFactory extends AbstractService
{

    /**
     * Actual Season Services for league tables and schedules.
     * Integration of optional translation feature (i18N)
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return ActualSeasonService
     * @throws RuntimeException
     *
     */
    public function createService(ServiceLocatorInterface $services)
    {

        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }

        //configuration
        $textDomain = isset($config['League']['text_domain']) ?
            $config['League']['text_domain'] : null;

        $this->setMapperFactory($services->get('League\Factory\MapperFactory'));

        //optional translator
        $translator = $services->get('translator');
        $this->setTranslator($translator, $textDomain);

        return $this;

    }


     /**
     * get the new season. The new season is the season following the actual or
     * last season.
     *
     * @return mixed null|Season
     */
    public function getNewSeason()
    {
        return $this->getMapper('season')->getNewSeason();
    }


    public function getLeaguesInSeason()
    {
       $result = array();
       $season = $this->getNewSeason();
       if(null===$season) {
           $result;
       }

       $allLeagues = $this->getMapper('league')
                          ->getLeaguesInSeason($season->getId());

       foreach($allLeagues as $league) {
           $result[$league->getId()] = $league->getTitle();
       }
       return $result;

    }

    public function getFreePlayers()
    {
       $result = array();
       $season = $this->getNewSeason();
       if(null===$season) {
           $result;
       }

       $allPlayers = $this->getMapper('player')
                          ->getFreePlayersForSeason($season->getId());

       foreach($allPlayers as $player) {
           $result[$player->getId()] = $player->getName();
       }
       return $result;

    }

    public function getPlayers()
    {
       $season = $this->getNewSeason();
       if(null===$season) {
           null;
       }

       return $this->getMapper('player')
                   ->getPlayersInSeason($season->getId());

    }

    /**
     * adding an user
     *
     * @param Request $request
     * @param array $data
     */
    public function addPlayer($data)
    {


        $seasonId = $data['sid'];
        $leagueId = $data['lid'];


        foreach($data['player'] as $playerId) {


            $player = new Player();

            $player->setSid($seasonId);
            $player->setLid($leagueId);
            $player->setUid($playerId);

            $this->getMapper('player')->save($player);

        }


    }




}


