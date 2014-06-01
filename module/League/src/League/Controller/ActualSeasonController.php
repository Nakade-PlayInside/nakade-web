<?php
namespace League\Controller;

use League\Standings\MatchInfo;
use League\Standings\MatchStats;
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
        $userId = $this->getUserId();

        //todo: show all actual tables
        //todo: show user's table as the active table
        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::NEW_SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $matchMapper \League\Mapper\MatchMapper */
        $matchMapper = $this->getRepository()->getMapper(RepositoryService::MATCH_MAPPER);

        $matches = $matchMapper->getActualMatchesByUser($season->getId(), $userId);

       return new ViewModel(
           array(
              'season' => $season,
              'matches' => $matches,
           )
       );
    }

    /**
     * @return ViewModel
     */
    public function myScheduleAction()
    {
        $uid = $this->getUserId();

        return new ViewModel(
            array(
                'userId' =>  $uid,
                'title'   => $this->getService()->getMyScheduleTitle($uid),
                'matches' => $this->getService()->getMySchedule($uid),
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
       $uid = $this->getUserId();

        //sorting the table
       $sorting  = $this->params()->fromRoute('sort', null);

       return new ViewModel(
           array(
              'table'       => $this->getService()
                                    ->getLeagueTable($uid, $sorting),
              'tiebreakers' => $this->getService()
                                    ->getTiebreakerNames(),
              'title'       => $this->getService()
                                    ->getTableTitle($uid),

           )
       );
    }

    /**
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public function iCalAction()
    {

        $fileName = 'myNakade.iCal';

        $uid = $this->getUserId();
        $matches = $this->getService()->getMySchedule($uid);

        $content = $this->getICalService()->getICalSchedule($uid, $matches);

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
     * @return \Zend\Http\Response | int
     */
    private function getUserId()
    {
        $user = $this->identity();

        if (null == $user) {
            return $this->redirect()->toRoute('login');
        }

        return $user->getId();
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
