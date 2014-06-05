<?php
namespace League\Controller;

use League\Entity\Result;
use League\Services\LeagueFormService;
use League\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;

/**
 * processing user input, in detail results and postponing
 * matches.
 *
 */
class ResultController extends AbstractController
{

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


        $matches = $resultMapper->getOpenResultsBySeason($season->getId());


       return new ViewModel(
           array(
                'season' =>  $season,
                'matches' =>  $matches
           )
       );

    }

    /**
    * showing all results of the actual user. All open matches are indicated.
    *
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
    * Form for adding a result
    *
    * @return \Zend\Http\Response|ViewModel
    */
    public function addAction()
    {

        $pid  = (int) $this->params()->fromRoute('id', 0);
        $userId = $this->identity()->getId();

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);
        /* @var $match \Season\Entity\Match */
        $match = $resultMapper->getMatchById($pid);

        if ($match->hasResult() || ($match->getBlack()->getId() != $userId && $match->getWhite()->getId() != $userId)) {
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
            if ($postData['button']['cancel']) {
                return $this->redirect()->toRoute('result', array('action' => 'myResult'));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData();var_dump($data->getPoints());die;
                return $this->redirect()->toRoute('season', array('action' => 'create'));
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
    public function matchDayAction()
    {
        $matchDay=2;
        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        $season = $seasonMapper->getActiveSeasonByAssociation(1);

        /* @var $resultMapper \League\Mapper\ResultMapper */
        $resultMapper = $this->getRepository()->getMapper(RepositoryService::RESULT_MAPPER);

        $matches = $resultMapper->getMatchDayBySeason($season->getId(), $matchDay);

        return new ViewModel(
            array(
                'matchDay' =>  $matchDay,
                'matches' =>  $matches
            )
        );
    }


}
