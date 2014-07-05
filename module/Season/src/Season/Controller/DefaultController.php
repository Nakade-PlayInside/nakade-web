<?php
namespace Season\Controller;

use Nakade\Abstracts\AbstractController;
use \Season\Services\RepositoryService;
use Season\Services\SeasonFormService;

/**
 * Class DefaultController
 *
 * @package Season\Controller
 */
class DefaultController extends AbstractController
{

    /**
     * @return \Season\Services\RepositoryService
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return \Season\Mapper\SeasonMapper
     */
    public function getSeasonMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
    }

    /**
     * @return \Season\Mapper\LeagueMapper
     */
    public function getLeagueMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
    }

    /**
     * @return \Season\Form\ParticipantForm
     */
    public function getParticipantForm()
    {
        return $this->getForm(SeasonFormService::PARTICIPANT_FORM);
    }

    /**
     * @return \Season\Form\LeagueForm
     */
    public function getLeagueForm()
    {
        return $this->getForm(SeasonFormService::LEAGUE_FORM);
    }

    /**
     * @return \Season\Form\SeasonForm
     */
    public function getSeasonForm()
    {
        return $this->getForm(SeasonFormService::SEASON_FORM);
    }

    /**
     * @return \Season\Form\MatchDayForm
     */
    public function getMatchDayForm()
    {
        return $this->getForm(SeasonFormService::MATCH_DAY_FORM);
    }

    /**
     * @return \Season\Form\MatchDayConfigForm
     */
    public function getMatchDayConfigForm()
    {
        return $this->getForm(SeasonFormService::MATCH_DAY_CONFIG_FORM);
    }


}
