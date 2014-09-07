<?php
namespace League\Controller;

use League\Services\ICalService;
use League\Services\LeagueFormService;
use Zend\View\Model\ViewModel;
use League\Services\MailService;


/**
 * processing user input, in detail results and postponing
 * matches.
 *
 */
class TimeTableController extends DefaultController
{
    /**
     * @var ICalService
     */
    private $iCal=null;

   /**
    * showing all open results of the actual season by an admin
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {

        //todo: paginator for leagues
        //todo: filter for associations depending on role of the requesting user
        $season = $this->getActualSeason();

        return new ViewModel(
            array(
                'season' =>  $season,
                'matches' =>  $this->getResultMapper()->getAllOpenResultsBySeason($season->getId())
            )
        );

    }

    /**
    * Form for editing a match appointment
    *
    * @return \Zend\Http\Response|ViewModel
    *
    * @throws \RuntimeException
    */
    public function editAction()
    {
        $id  = (int) $this->params()->fromRoute('id', 0);

        $match = $this->getResultMapper()->getMatchById($id);

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
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('timeTable');
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData();
                $this->getResultMapper()->save($data);

                /* @var $mail \League\Mail\ScheduleMail */
                $mail = $this->getMailService()->getMail(MailService::SCHEDULE_MAIL);
                $mail->setMatch($data);
                $mail->sendMail($data->getBlack());
                $mail->sendMail($data->getWhite());

                $this->flashMessenger()->addSuccessMessage('Date updated.');
                return $this->redirect()->toRoute('timeTable');
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }

        }

       return new ViewModel(
           array(
                'form'    => $form
           )
       );
    }

    /**
     * Shows actual match plan of a user and his results.
     * If user is not in  a league, the top league schedule
     * is shown.
     *
     * @return array|ViewModel
     */
    public function scheduleAction()
    {
        $leagueNo  = $this->params()->fromRoute('league', null);
        $league = $this->getActualLeague($leagueNo);

        return new ViewModel(
            array(
                'league' => $league,
                'matches' => $this->getScheduleMapper()->getScheduleByLeague($league),
                'paginator' => $this->getLeaguePaginator()->getPagination($league->getNumber()),
            )
        );
    }


    /**
     * widget schedule
     *
     * @return ViewModel
     */
    public function myScheduleAction()
    {
        $userId = $this->identity()->getId();

        $season = $season = $this->getActualSeason();
        $league = $this->getScheduleMapper()->getLeagueByUser($season->getId(), $userId);

        $matches = array();
        if (!is_null($league)) {
            $matches = $this->getScheduleMapper()->getMyScheduleByUser($league->getId(), $userId);
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
        $userId = $this->identity()->getId();

        $season = $season = $this->getActualSeason();
        $league = $this->getScheduleMapper()->getLeagueByUser($season->getId(), $userId);

        $matches = array();
        if (!is_null($league)) {
            $matches = $this->getScheduleMapper()->getMyScheduleByUser($league->getId(), $userId);
        }

        return $this->getICalService()->getSchedule($userId, $matches);

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
