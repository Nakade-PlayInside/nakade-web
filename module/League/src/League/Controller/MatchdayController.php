<?php
namespace League\Controller;

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

       return new ViewModel(

           array(
                'matches' =>  $this->getService()->getOpenMatches()
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
