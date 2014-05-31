<?php
namespace Season\Form;

use Season\Entity\League;
use Nakade\Abstracts\AbstractForm;
use Season\Entity\Season;
use Season\Services\SeasonFieldsetService;
use Season\Services\RepositoryService;

/**
 * Class BaseLeagueForm
 *
 * @package Season\Form
 */
abstract class BaseForm extends AbstractForm
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
     * @return \Season\Services\RepositoryService
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
     * @return bool
     */
    public function hasAssignedPlayers()
    {
        return !empty($this->assignedPlayers);
    }

    /**
     * @return array
     */
    public function getAvailablePlayers()
    {
        return $this->availablePlayers;
    }

    /**
     * @return bool
     */
    public function hasAvailablePlayers()
    {
        return !empty($this->availablePlayers);
    }

    /**
     * @return mixed
     */
    abstract protected function prepareForm();

    /**
     * @param int $leagueId
     *
     * @return array
     */
    protected function getAssignedPlayersByRepository($leagueId)
    {
        $list = array();
        /* @var $repository \Season\Mapper\LeagueMapper */
        $repository = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        $playerList = $repository->getAssignedPlayersByLeague($leagueId);

        /* @var $player \Season\Entity\Participant */
        foreach ($playerList as $player) {
            $list[$player->getId()] = $player->getUser()->getName();
        }

        return $list;
    }

    /**
     * @param int $seasonId
     *
     * @return array
     */
    protected function getAvailablePlayersByRepository($seasonId)
    {
        $list = array();
        /* @var $repository \Season\Mapper\LeagueMapper */
        $repository = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        $playerList = $repository->getAvailableParticipantsBySeason($seasonId);

        /* @var $player \Season\Entity\Participant */
        foreach ($playerList as $player) {
            $list[$player->getId()] = $player->getUser()->getName();
        }

        return $list;
    }

}
