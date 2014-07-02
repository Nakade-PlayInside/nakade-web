<?php
namespace Season\Schedule;

use Season\Services\RepositoryService;
/**
 * Class Schedule
 *
 * @package Season\Schedule
 */
class Schedule
{
    private $matchDates;
    private $repositoryService;


    /**
     * @param RepositoryService $repositoryService
     */
    public function __construct(RepositoryService $repositoryService)
    {
        $this->repositoryService = $repositoryService;
    }

    public function getSchedule($seasonId)
    {
        $leagues = $this->getLeagueMapper()->getLeaguesBySeason($seasonId);
        $this->matchDays = $this->getSeasonMapper()->getMatchDaysBySeason($seasonId);

        /* @var $league \Season\Entity\League */
        foreach ($leagues as $league) {
            $players = $this->getParticipantMapper()->getParticipantsByLeague($league->getId());
        }
    }

    /**
     * @return mixed
     */
    public function getMatchDates()
    {
        return $this->matchDates;
    }

    /**
     * @return \Season\Services\RepositoryService
     */
    public function getRepositoryService()
    {
        return $this->repositoryService;
    }

    /**
     * @return \Season\Mapper\LeagueMapper
     */
    public function getLeagueMapper()
    {
        $repo = $this->getRepositoryService();
        return $repo->getMapper(RepositoryService::LEAGUE_MAPPER);
    }

    /**
     * @return \Season\Mapper\ParticipantMapper
     */
    public function getParticipantMapper()
    {
        $repo = $this->getRepositoryService();
        return $repo->getMapper(RepositoryService::PARTICIPANT_MAPPER);
    }

    /**
     * @return \Season\Mapper\SeasonMapper
     */
    public function getSeasonMapper()
    {
        $repo = $this->getRepositoryService();
        return $repo->getMapper(RepositoryService::SEASON_MAPPER);
    }

}
