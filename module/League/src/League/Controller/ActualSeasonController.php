<?php
namespace League\Controller;

use League\Standings\MatchStats;
use League\Standings\Sorting\SortingInterface;
use Nakade\Abstracts\AbstractController;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use League\Services\ICalService;
use Zend\Http\PhpEnvironment\Response as iCalResponse;
use Zend\Http\Headers;
use League\Services\RepositoryService;
use League\Standings\Sorting\PlayerSorting as SORT;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSeasonServiceFactory is needed to be set.
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class ActualSeasonController extends AbstractController
{
    /**
     * @var ICalService
     */
    private $iCal=null;

    /**
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {
        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::NEW_SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $leagueMapper \League\Mapper\LeagueMapper */
        $leagueMapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        $topLeague = $leagueMapper->getTopLeagueBySeason($season->getId());

        /* @var $matchMapper \League\Mapper\MatchMapper */
        $matchMapper = $this->getRepository()->getMapper(RepositoryService::MATCH_MAPPER);
        $matches = $matchMapper->getMatchesByLeague($topLeague->getId());

        //var_dump($matches);die;
        $info = new MatchStats($matches);
        $players = $info->getMatchStats();
        $sorting = SORT::getInstance();
        $sorting->sorting($players);

        return new ViewModel(
            array(
              'league'   => $topLeague,
              'table'    => $players
            )
        );
    }

    /**
    * Shows actual matchplan of a user and his results.
    * If user is not in  a league, the top league schedule
    * is shown.
    *
    * @return array|ViewModel
    */
    public function scheduleAction()
    {
        $userId = $this->identity()->getId();

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::NEW_SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $matchMapper \League\Mapper\ScheduleMapper */
        $matchMapper = $this->getRepository()->getMapper(RepositoryService::SCHEDULE_MAPPER);

        $league = $matchMapper->getLeagueByUser($season->getId(), $userId);
        if (is_null($league)) {
            /* @var $leagueMapper \League\Mapper\LeagueMapper */
            $leagueMapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
            $league = $leagueMapper->getTopLeagueBySeason($season->getId());
        }

        $matches = $matchMapper->getScheduleByLeague($league);

       return new ViewModel(
           array(
              'league' => $league,
              'matches' => $matches,
           )
       );
    }

    /**
     * @return ViewModel
     */
    public function myScheduleAction()
    {
        $userId = $this->identity()->getId();

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::NEW_SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $matchMapper \League\Mapper\ScheduleMapper */
        $matchMapper = $this->getRepository()->getMapper(RepositoryService::SCHEDULE_MAPPER);

        /* @var $league \Season\Entity\League */
        $league = $matchMapper->getLeagueByUser($season->getId(), $userId);
        $matches = array();
        if (!is_null($league)) {
            $matches = $matchMapper->getMyScheduleByUser($league->getId(), $userId);
        }

        return new ViewModel(
            array(
                'league' => $league,
                'matches' => $matches,
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
       $seasonMapper = $this->getRepository()->getMapper(RepositoryService::NEW_SEASON_MAPPER);
       $season = $seasonMapper->getActiveSeasonByAssociation(1);

       /* @var $matchMapper \League\Mapper\ScheduleMapper */
       $matchMapper = $this->getRepository()->getMapper(RepositoryService::SCHEDULE_MAPPER);

       $league = $matchMapper->getLeagueByUser($season->getId(), $userId);
       if (is_null($league)) {
           /* @var $leagueMapper \League\Mapper\LeagueMapper */
           $leagueMapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
           $league = $leagueMapper->getTopLeagueBySeason($season->getId());
       }

       /* @var $matchMapper \League\Mapper\MatchMapper */
       $matchMapper = $this->getRepository()->getMapper(RepositoryService::MATCH_MAPPER);
       $matches = $matchMapper->getMatchesByLeague($league->getId());


       //sorting the table
       $sortBy  = $this->params()->fromRoute('sort', SortingInterface::BY_POINTS);

       $info = new MatchStats($matches);
       $players = $info->getMatchStats();
       $sorting = SORT::getInstance();
       $sorting->sorting($players, $sortBy);

       return new ViewModel(
           array(
              'table'   => $players,
              'league'  => $league,

           )
       );
    }

    /**
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public function iCalAction()
    {

        $fileName = 'myNakade.iCal';

        $userId = $this->identity()->getId();

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::NEW_SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $matchMapper \League\Mapper\ScheduleMapper */
        $matchMapper = $this->getRepository()->getMapper(RepositoryService::SCHEDULE_MAPPER);

        /* @var $league \Season\Entity\League */
        $league = $matchMapper->getLeagueByUser($season->getId(), $userId);
        $matches = array();
        if (!is_null($league)) {
            $matches = $matchMapper->getMyScheduleByUser($league->getId(), $userId);
        }

        $content = $this->getICalService()->getICalSchedule($userId, $matches);

        $headers = new Headers();
        $headers->addHeaderLine('Content-Type', 'text/calendar; charset=utf-8')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        $response = new iCalResponse();
        $response->setStatusCode(200);
        $response->setHeaders($headers);
        $response->setContent($content);

        return $response;

    }

    /**
     * @param ICalService $service
     *
     * @return $this
     */
    public function setICalService(ICalService $service)
    {
        $this->iCal = $service;
        return $this;
    }

    /**
     * @return ICalService
     */
    public function getICalService()
    {
        return $this->iCal;
    }


}
