<?php
namespace Season\Controller;


use Season\Entity\Participant;
use Season\Services\MailService;
use Zend\View\Model\ViewModel;

class PlayerController extends DefaultController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);
//todo: validate matchDay are not existing
        //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);

        return new ViewModel(array(
               //'players' => $this->getService()->getPlayers(),
               'season' => $season,
               'invited' => $this->getSeasonMapper()->getInvitedUsersBySeason($season->getId()),
               'available' => $this->getSeasonMapper()->getAvailablePlayersBySeason($season->getId()),
               'accepted' => $this->getSeasonMapper()->getAcceptingUsersBySeason($season->getId())
           )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);
//todo: validate matchDay are not existing
         //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);

       $form = $this->getParticipantForm();
       $form->bindEntity($season);

       /* @var $request \Zend\Http\Request */
       $request = $this->getRequest();
       if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $object = $form->getData();
                $now = new \DateTime();
                $playerList = $object->getAvailablePlayers();

                /* @var $mail \Season\Mail\InvitationMail */
                $mail = $this->getMailService()->getMail(MailService::INVITATION_MAIL);

                foreach ($playerList as $user) {
                    $acceptString = md5(uniqid(rand(), true));

                    $participant = new Participant();
                    $participant->setUser($user);
                    $participant->setSeason($season);
                    $participant->setDate($now);
                    $participant->setAcceptString($acceptString);

                    $this->getSeasonMapper()->save($participant);

                    $mail->setParticipant($participant);
                    $mail->sendMail($user);
                }

                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
       }


        return new ViewModel(
            array(
              'form' => $form,
            )
        );
    }

}
