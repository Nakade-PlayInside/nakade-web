<?php
namespace League\Controller;

use League\Services\LeagueFormService;
use League\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * processing user input, in detail results and postponing
 * matches.
 *
 */
class MatchDayController extends AbstractController
{

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
            return $this->redirect()->toRoute('matchDay');
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
                return $this->redirect()->toRoute('matchDay');
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData();
                $mapper->save($data);
                //todo: email for both

                return $this->redirect()->toRoute('matchDay');
            }
        }

       return new ViewModel(
           array(
                'form'    => $form
           )
       );
    }

}
