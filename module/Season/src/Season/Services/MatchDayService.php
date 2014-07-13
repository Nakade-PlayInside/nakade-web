<?php

namespace Season\Services;

use Season\Entity\League;
use Season\Entity\Season;
use Season\Entity\Schedule;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MatchDayService
 *
 * @package Season\Services
 */
class MatchDayService implements FactoryInterface
{
    private $repository;
    private $minPlayers = 3;

    /**
     * @param ServiceLocatorInterface $services
     *
     * @return $this
     *
     * @throws \RuntimeException
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $repository \Season\Services\RepositoryService */
        $this->repository = $services->get('Season\Services\RepositoryService');

        if (is_null($this->repository)) {
            throw new \RuntimeException(
                sprintf('Repository service could not be found.')
            );
        }
        return $this;
    }

    /**
     * @param int $seasonId
     */
    private function cleanupEmptyLeagues($seasonId)
    {
        $emptyLeagues = $this->getLeagueMapper()->getEmptyLeaguesBySeason($seasonId);
        foreach ($emptyLeagues as $league) {
            $this->getLeagueMapper()->delete($league);
        }
    }

    /**
     * @param int $seasonId
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    private function hasEnoughPlayersInLeagues($seasonId)
    {
        $leagues = $this->getLeagueMapper()->getLeagueInfoBySeason($seasonId);
        foreach ($leagues as $league) {
            if ($this->isValidLeague($league)) {
                throw new \RuntimeException(
                    sprintf(
                        'There are leagues with less than %d players. Please assign more players or remove player.',
                        $this->getMinPlayers()
                    )
                );
            }
        }
        return true;
    }

    /**
     * @param Season $season
     *
     * @return Schedule
     */
    public function getScheduleEntity(Season $season)
    {
        $this->cleanupEmptyLeagues($season->getId());
        $this->hasEnoughPlayersInLeagues($season->getId());

        $noOfMatchDays = $this->getSeasonMapper()->getMaxParticipantsInLeagueBySeason($season->getId());
        return new Schedule($season, $noOfMatchDays);
    }

    /**
     * @param League $league
     *
     * @return bool
     */
    private function isValidLeague(League $league)
    {
        return $league->getNoPlayers() < $this->getMinPlayers();
    }

    /**
     * @return int
     */
    private function getMinPlayers()
    {
        return $this->minPlayers;
    }

    /**
     * @return \Season\Services\RepositoryService
     */
    private function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Season\Mapper\SeasonMapper
     */
    private function getSeasonMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
    }

    /**
     * @return \Season\Mapper\LeagueMapper
     */
    private function getLeagueMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
    }

}
