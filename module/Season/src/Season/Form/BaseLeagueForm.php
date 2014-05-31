<?php
namespace Season\Form;

use Season\Entity\League;
use Nakade\Abstracts\AbstractForm;
use Season\Entity\Season;
use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;

/**
 * Class BaseLeagueForm
 *
 * @package Season\Form
 */
abstract class BaseLeagueForm extends AbstractForm
{

    protected  $service;
    protected $repository;
    protected $season;
    protected $league;
    protected $availablePlayers=array();
    protected $assignedPlayers=array();
    protected $associationName='unnamed';
    protected $seasonNumber=1;
    protected $leagueNumber=1;

    /**
     * @param League $league
     */
    public function setLeague(League $league)
    {
        $this->league = $league;
    }

    /**
     * @return League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    /**
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }


    /**
     * @return SeasonFieldsetService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return \Season\Mapper\LeagueMapper
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return int
     */
    public function getLeagueNumber()
    {
        return $this->leagueNumber;
    }

    /**
     * @return int
     */
    public function getSeasonNumber()
    {
        return $this->seasonNumber;
    }

    /**
     * @return string
     */
    public function getAssociationName()
    {
        return $this->associationName;
    }

    /**
     * @return array
     */
    public function getAssignedPlayers()
    {
        return $this->assignedPlayers;
    }

    /**
     * @return array
     */
    public function getAvailablePlayers()
    {
        return $this->availablePlayers;
    }

    protected function prepareForm()
    {

        if (!is_null($this->getLeague())) {
            $this->season = $this->getLeague()->getSeason();
            $this->leagueNumber = $this->getLeague()->getNumber();
            $this->assignedPlayers = $this->getAssignedPlayersByRepository($this->getLeague()->getId());
        }

        if (!is_null($this->getSeason())) {
            $this->associationName = $this->getSeason()->getAssociation()->getName();
            $this->seasonNumber = $this->getSeason()->getNumber();
            $this->availablePlayers = $this->getAvailablePlayersByRepository($this->getSeason()->getId());
        }
    }

    protected function getAssignedPlayersByRepository($leagueId)
    {
        $list = array();
        $playerList = $this->getRepository()->getAssignedPlayersByLeague($leagueId);

        /* @var $player \Season\Entity\Participant */
        foreach ($playerList as $player) {
            $list[$player->getId()] = $player->getUser()->getName();
        }

        return $list;
    }

    protected function getAvailablePlayersByRepository($seasonId)
    {
        $list = array();
        $playerList = $this->getRepository()->getAvailableParticipantsBySeason($seasonId);

        /* @var $player \Season\Entity\Participant */
        foreach ($playerList as $player) {
            $list[$player->getId()] = $player->getUser()->getName();
        }

        return $list;
    }

}
