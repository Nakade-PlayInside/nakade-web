<?php
namespace League\Controller;

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
     * @return \League\Services\RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

}
