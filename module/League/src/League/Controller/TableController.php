<?php
namespace League\Controller;

use League\Services\TableService;
use League\Standings\Sorting\SortingInterface;
use Nakade\Abstracts\AbstractController;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use League\Services\RepositoryService;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSeasonServiceFactory is needed to be set.
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class TableController extends AbstractController
{

    private $tableService;

    /**
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {
        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $leagueMapper \League\Mapper\LeagueMapper */
        $leagueMapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        $topLeague = $leagueMapper->getTopLeagueBySeason($season->getId());
        $matches = $leagueMapper->getMatchesByLeague($topLeague->getId());

        return new ViewModel(
            array(
              'league'   => $topLeague,
              'table'    => $this->getTableService()->getPlayersTable($matches)
            )
        );
    }




    /**
    * Shows the user's league table. If user is not in a league, the
    * Top league is shown instead. The Table is sortable.
    *
    * @return array|ViewModel
    */
    public function tableAction()
    {
       $userId = $this->identity()->getId();

       /* @var $seasonMapper \Season\Mapper\SeasonMapper */
       $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
       $season = $seasonMapper->getActiveSeasonByAssociation(1);

       /* @var $matchMapper \League\Mapper\ScheduleMapper */
       $matchMapper = $this->getRepository()->getMapper(RepositoryService::SCHEDULE_MAPPER);

       /* @var $leagueMapper \League\Mapper\LeagueMapper */
       $leagueMapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
       $league = $matchMapper->getLeagueByUser($season->getId(), $userId);
       if (is_null($league)) {
           $league = $leagueMapper->getTopLeagueBySeason($season->getId());
       }
       $matches = $leagueMapper->getMatchesByLeague($league->getId());

       //sorting the table
       $sortBy  = $this->params()->fromRoute('sort', SortingInterface::BY_POINTS);

       return new ViewModel(
           array(
              'table'   => $this->getTableService()->getPlayersTable($matches, $sortBy),
              'league'  => $league,

           )
       );
    }



    /**
     * @param TableService $service
     *
     * @return $this
     */
    public function setTableService(TableService $service)
    {
        $this->tableService = $service;
        return $this;
    }

    /**
     * @return TableService
     */
    public function getTableService()
    {
        return $this->tableService;
    }


}
