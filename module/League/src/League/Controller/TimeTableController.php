<?php
namespace League\Controller;

use League\Services\ICalService;
use League\Services\LeagueFormService;
use League\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Response as iCalResponse;
use Zend\Http\Headers;


/**
 * processing user input, in detail results and postponing
 * matches.
 *
 */
class TimeTableController extends AbstractController
{
    /**
     * @var ICalService
     */
    private $iCal=null;

   /**
    * showing all open results of the actual season
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {

        //todo: paginator for leagues
        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);

        $matches = $resultMapper->getAllOpenResultsBySeason($season->getId());

        return new ViewModel(
            array(
                'season' =>  $season,
                'matches' =>  $matches
            )
        );

    }

    /**
    * Form for edit a result
    *
    * @return \Zend\Http\Response|ViewModel
    */
    public function editAction()
    {

        $id  = (int) $this->params()->fromRoute('id', 0);

        /* @var $mapper \League\Mapper\ResultMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);
        $match = $mapper->getMatchById($id);

        if (is_null($match)) {
            return $this->redirect()->toRoute('timeTable');
        }

        /* @var $form \League\Form\MatchDayForm */
        $form = $this->getForm(LeagueFormService::MATCHDAY_FORM);
        $form->bind($match);


        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if ($postData['button']['cancel']) {
                return $this->redirect()->toRoute('timeTable');
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData();
                $mapper->save($data);
                //todo: email for both for new date

                return $this->redirect()->toRoute('timeTable');
            }
        }

       return new ViewModel(
           array(
                'form'    => $form
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
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
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
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
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
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public function iCalAction()
    {

        $fileName = 'myNakade.iCal';

        $userId = $this->identity()->getId();

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
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
