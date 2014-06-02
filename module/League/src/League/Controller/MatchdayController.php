<?php
namespace League\Controller;

use League\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * processing user input, in detail results and postponing
 * matches.
 *
 */
class MatchdayController extends AbstractController
{

   /**
    * showing all open results of the actual season
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {

        /* @var $seasonMapper \Season\Mapper\SeasonMapper */
        $seasonMapper = $this->getRepository()->getMapper(RepositoryService::NEW_SEASON_MAPPER);
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

        /* @var $match \League\Entity\Match */
        $match = $this->getService()->getMatch($id);

        if (is_null($match)) {
            return $this->redirect()->toRoute('matchday');
        }
        $form = $this->getForm('matchday');//->setResultFormValues($pid);
        $form->bindEntity($match);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('matchday');
            }

            $form->setData($postData);

            if ($form->isValid()) {


                $datetime = $postData['date']. ' ' . $postData['time'];
                $temp = new \DateTime($datetime);
                $match->setDate($temp);

                //updating for iCal
                $sequence = $match->getSequence() + 1;
                $match->setSequence($sequence);

                if ($postData['changeColors']) {

                    $black = $match->getBlack();
                    $white = $match->getWhite();

                    $match->setBlack($white);
                    $match->setWhite($black);
                }

                $this->getService()->getMapper('match')->save($match);
                return $this->redirect()->toRoute('matchday');
            }
        }

       return new ViewModel(
           array(
             // 'id'      => $pid,
                'match'   => $match,
                'form'    => $form
           )
       );
    }

}
