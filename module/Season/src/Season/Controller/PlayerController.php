<?php
namespace Season\Controller;

use Season\Entity\Participant;
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
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('season', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);

        /* @var $playerMapper \Season\Mapper\ParticipantMapper */
        $playerMapper = $this->getRepository()->getMapper('participant');
       return new ViewModel(
           array(
               //'players' => $this->getService()->getPlayers(),
               'season' => $season,
               'invited' => $playerMapper->getInvitedUsersBySeason($season->getId()),
               'available' => $playerMapper->getAvailablePlayersBySeason($season->getId()),
               'accepted' => $playerMapper->getAcceptingUsersBySeason($season->getId())
           )
       );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('season', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);

       /* @var $form \Season\Form\ParticipantForm */
       $form = $this->getForm('participant');
       $form->setSeason($season);
       $form->init();

       /* @var $request \Zend\Http\Request */
       $request = $this->getRequest();
       if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('season');
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $now = new \DateTime();
                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                foreach ($data['players'] as $userId) {

                    $myPlayer = new Participant();

                    /* @var $user \User\Entity\User */
                    $user = $mapper->getEntityManager()->getReference('User\Entity\User', $userId);
                    $myPlayer->setUser($user);
                    $myPlayer->setSeason($season);
                    $myPlayer->setDate($now);
                    $mapper->save($myPlayer);

                }
                //$this->getService()->addPlayer($data);

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
