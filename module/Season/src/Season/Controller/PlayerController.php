<?php
namespace Season\Controller;

use Season\Services\RepositoryService;
use Season\Entity\Participant;
use Season\Services\SeasonFormService;
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
        $mapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);

        return new ViewModel(array(
               //'players' => $this->getService()->getPlayers(),
               'season' => $season,
               'invited' => $mapper->getInvitedUsersBySeason($season->getId()),
               'available' => $mapper->getAvailablePlayersBySeason($season->getId()),
               'accepted' => $mapper->getAcceptingUsersBySeason($season->getId())
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
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);

       /* @var $form \Season\Form\ParticipantForm */
       $form = $this->getForm(SeasonFormService::PARTICIPANT_FORM);
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

                foreach ($playerList as $user) {
                    $acceptString = md5(uniqid(rand(), true));

                    $participant = new Participant();
                    $participant->setUser($user);
                    $participant->setSeason($season);
                    $participant->setDate($now);
                    $participant->setAcceptString($acceptString);

                    $mapper->save($participant);
                }
                //$this->getService()->addPlayer($data);

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
