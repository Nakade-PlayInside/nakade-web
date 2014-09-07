<?php
namespace League\Controller;

use League\Services\LeagueFormService;
use Nakade\Pagination\ItemPagination;
use Zend\View\Model\ViewModel;
use League\Services\MailService;
use League\Services\PaginationService;

/**
 * processing user input, in detail results and postponing
 * matches.
 *
 */
class ResultController extends DefaultController
{
    const HOME = 'result';

    /* @var $resultService \League\Services\ResultService */
    private $resultService;
    private $paginationService;

   /**
    * showing all open results of the actual season
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {

        $season = $this->getActualSeason();

        return new ViewModel(
            array(
                'season' =>  $season,
                'matches' =>  $this->getResultMapper()->getActualOpenResultsBySeason($season->getId())
            )
        );
    }

    /**
     * showing all results of the actual season
     *
     * @return array|ViewModel
     */
    public function allResultsAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getActualSeason();
        $total = $this->getResultMapper()->getActualResultsByPages($season->getId());;
        $pagination = new ItemPagination($total);

        return new ViewModel(
            array(
                'season' =>  $season,
                'matches' =>  $this->getResultMapper()
                        ->getActualResultsByPages($season->getId(), $pagination->getOffset($page)),
                'paginator' => $pagination->getPagination($page),
            )
        );
    }


    /**
    * showing all results of the actual user. All open matches are indicated.
    *
    * @return \Zend\Http\Response|ViewModel
    */
    public function myResultAction()
    {
        $season = $this->getActualSeason();
        return new ViewModel(
            array(
               'season' =>  $season,
               'matches' =>  $this->getResultMapper()
                       ->getResultsByUser($season->getId(), $this->identity()->getId()),
            )
        );

    }

    /**
     * @return \Zend\Http\Response|ViewModel
     *
     * @throws \RuntimeException
     */
    public function editAction()
    {
        $matchId  = (int) $this->params()->fromRoute('id', 0);

        $match = $this->getResultMapper()->getMatchById($matchId);

        /* @var $form \League\Form\ResultForm */
        $form = $this->getForm(LeagueFormService::RESULT_FORM);
        $form->bindEntity($match);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('result', array('action' => 'allResults'));
            }

            $form->setData($postData);
            if ($form->isValid()) {
                /* @var $data \Season\Entity\Match */
                $data = $form->getData();
                $this->getResultMapper()->save($data);

                /* @var $mail \League\Mail\ResultEditMail */
                $mail = $this->getMailService()->getMail(MailService::EDITED_RESULT__MAIL);
                $mail->setMatch($data);
                $mail->sendMail($data->getBlack());
                $mail->sendMail($data->getWhite());

                $this->flashMessenger()->addSuccessMessage('Result updated.');
                return $this->redirect()->toRoute(self::HOME, array('action' => 'allResults'));
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }

        }

        return new ViewModel(
            array(
                'match'   => $match,
                'form'    => $form
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     *
     * @throws \RuntimeException
     */
    public function addAction()
    {

        $matchId  = (int) $this->params()->fromRoute('id', 0);

        $match = $this->getResultMapper()->getMatchById($matchId);

        if (!$this->getService()->isAllowed($match)) {
            throw new \RuntimeException(
                sprintf('You are not allowed to enter a result on this match.')
            );
        }

        /* @var $form \League\Form\ResultForm */
        $form = $this->getForm(LeagueFormService::RESULT_FORM);
        $form->bindEntity($match);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('result', array('action' => 'myResult'));
            }

            $form->setData($postData);
            if ($form->isValid()) {
                /* @var $data \Season\Entity\Match */
                $data = $form->getData();
                $this->getResultMapper()->save($data);

                /* @var $mail \League\Mail\ResultMail */
                $mail = $this->getMailService()->getMail(MailService::RESULT_MAIL);
                $mail->setMatch($data);
                $mail->sendMail($data->getBlack());
                $mail->sendMail($data->getWhite());

                $this->flashMessenger()->addSuccessMessage('Result entered.');
                return $this->redirect()->toRoute(self::HOME, array('action' => 'success'));
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }

       return new ViewModel(
           array(
              'match'   => $match,
              'form'    => $form
           )
       );
    }

    /**
     * result widget
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function actualResultsAction()
    {
        $matchDay = $this->params()->fromRoute('id');
        $season = $this->getActualSeason();
        $topLeague = $this->getLeagueMapper()->getTopLeagueBySeason($season->getId());

        if (empty($matchDay)) {
            $matchDay = $this->getResultMapper()->getActualMatchDayByLeague($topLeague->getId());

            if ($matchDay==0) {
                $matchDay=1;
            }
        }

        $matches = $this->getResultMapper()->getMatchesByMatchDay($topLeague->getId(), $matchDay);

        return new ViewModel(
            array(
                'pagination' => $this->getPaginationService()->getPagination($topLeague->getId(), $matchDay),
                'legend' => $this->getResultService()->getLegendByMatches($matches),
                'matchDay' =>  $matchDay,
                'matches' =>  $matches
            )
        );
    }

    /**
     * @return ViewModel
     */
    public function successAction()
    {
        return new ViewModel(array());
    }

    /**
     * @return \League\Standings\Results
     */
    public function getResultService()
    {
        return $this->resultService;
    }

    /**
     * @param \League\Services\ResultService $resultService
     */
    public function setResultService($resultService)
    {
        $this->resultService = $resultService;
    }

    /**
     * @param PaginationService $paginationService
     */
    public function setPaginationService(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * @return PaginationService
     */
    public function getPaginationService()
    {
        return $this->paginationService;
    }

}
