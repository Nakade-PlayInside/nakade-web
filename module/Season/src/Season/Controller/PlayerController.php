<?php
namespace Season\Controller;

use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

class PlayerController extends AbstractController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        /* @var $mapper \Season\Mapper\ParticipantMapper */
        $mapper = $this->getRepository()->getMapper('participant');
        //var_dump(count($mapper->getAvailablePlayers()));die;

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation(1)) {
            return $this->redirect()->toRoute('season', array('action' => 'create'));
        }

        $season = $mapper->getNewSeasonByAssociation(1);

        /* @var $form \Season\Form\ParticipantForm */
        $form = $this->getForm('participant');
        $form->setSeason($season);
        $form->init();
       // $form->bind($season);

       return new ViewModel(
           array(
               //'players' => $this->getService()->getPlayers(),
               'form' => $form,

           )
       );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
       //make sure that there are leagues
       if (count($this->getService()->getLeaguesInSeason())==0) {
            $this->redirect()->toRoute('league/add');
       }

       $form = $this->getForm('player');

       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('season');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $this->getService()->addPlayer($data);

                return $this->redirect()->toRoute('season');
            }
       }


        return new ViewModel(
            array(
              'form' => $form,
            )
        );
    }

}
