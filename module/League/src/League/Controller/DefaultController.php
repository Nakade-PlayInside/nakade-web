<?php
namespace League\Controller;

use League\Pagination\LeaguePagination;
use Nakade\Abstracts\AbstractController;
use Zend\Http\Response;
use League\Services\RepositoryService;

/**
 * Class DefaultController
 *
 * @package League\Controller
 */
class DefaultController extends AbstractController
{

    /**
     * @return \Season\Mapper\SeasonMapper
     */
    public function getSeasonMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
    }

    /**
     * @return \League\Mapper\ScheduleMapper
     */
    public function getScheduleMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::SCHEDULE_MAPPER);
    }

    /**
     * @return \League\Mapper\LeagueMapper
     */
    public function getLeagueMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
    }

    /**
     * @return \League\Mapper\ResultMapper
     */
    public function getResultMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);
    }

    /**
     * @return \League\Services\RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Season\Entity\Season|null
     */
    protected function getActualSeason()
    {
        $association = 1;
        return $this->getSeasonMapper()->getActiveSeasonByAssociation($association);
    }

    /**
     * @param null|int $leagueNo
     *
     * @return null|\Season\Entity\League
     */
    protected function getActualLeague($leagueNo=null)
    {
        $season = $this->getActualSeason();
        $league = null;

        if(is_null($season)) {
            return  $league;
        }

        if(!is_null($leagueNo)) {
            return $this->getLeagueMapper()->getLeagueByNumber($season, $leagueNo);
        }

        $user = $this->identity();
        if(!empty($user)) {
            $league = $this->getScheduleMapper()->getLeagueByUser($season->getId(), $user->getId());
        }

        if (empty($league)) {
            $league = $this->getLeagueMapper()->getTopLeagueBySeason($season->getId());
        }

        return  $league;
    }

    /**
     * @return LeaguePagination
     */
    protected function getLeaguePaginator()
    {
        $leaguesInSeason = array();
        $season = $this->getActualSeason();

        if(!is_null($season)) {
            $leaguesInSeason = $this->getLeagueMapper()->getLeaguesBySeason($season->getId());
        }

        return  new LeaguePagination($leaguesInSeason);
    }


}
