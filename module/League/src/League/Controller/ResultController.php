<?php
namespace League\Controller;

use League\Services\LeagueFormService;
use League\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Nakade\Pagination\ItemPagination;
use Zend\View\Model\ViewModel;
use League\Services\MailService;
use League\Services\PaginationService;

/**
 * processing user input, in detail results and postponing
 * matches.
 *
 */
class ResultController extends AbstractController
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

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);


        $matches = $resultMapper->getActualOpenResultsBySeason($season->getId());

        return new ViewModel(
            array(
                'season' =>  $season,
                'matches' =>  $matches
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

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);

        $total = $resultMapper->getActualResultsByPages($season->getId());;
        $pagination = new ItemPagination($total);

        return new ViewModel(
            array(
                'season' =>  $season,
                'matches' =>  $resultMapper->getActualResultsByPages($season->getId(), $pagination->getOffset($page)),
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
        $userId = $this->identity()->getId();

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);

        $matches = $resultMapper->getResultsByUser($season->getId(), $userId);

        return new ViewModel(
            array(
               'season' =>  $season,
               'matches' =>  $matches
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

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);
        /* @var $match \Season\Entity\Match */
        $match = $resultMapper->getMatchById($matchId);

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
                $resultMapper->save($data);

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

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);
        /* @var $match \Season\Entity\Match */
        $match = $resultMapper->getMatchById($matchId);

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
                $resultMapper->save($data);

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
     * results by match day
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function actualResultsAction()
    {
        $matchDay = $this->params()->fromRoute('id');

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $leagueMapper \League\Mapper\LeagueMapper */
        $leagueMapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        $topLeague = $leagueMapper->getTopLeagueBySeason($season->getId());

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);

        if (empty($matchDay)) {
            $matchDay = $resultMapper->getActualMatchDayByLeague($topLeague->getId());
        }

        $matches = $resultMapper->getMatchesByMatchDay($topLeague->getId(), $matchDay);
        $pagination = $this->getPaginationService()->getPagination($topLeague->getId(), $matchDay);
        $legend = $this->getResultService()->getLegendByMatches($matches);

        return new ViewModel(
            array(
                'pagination' => $pagination,
                'legend' => $legend,
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
