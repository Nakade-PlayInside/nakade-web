<?php
namespace Season\Controller;

use Season\Entity\League;
use Zend\View\Model\ViewModel;

/**
 * For adding leagues after creating a new season
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class LeagueController extends DefaultController
{

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);

        $noLeagues = $this->getSeasonMapper()->getNoOfLeaguesInSeason($season->getId());
        $season->setNoLeagues($noLeagues);

        return new ViewModel(
            array(
               'season' => $season,
               'accepted' => count($this->getSeasonMapper()->getAcceptingUsersBySeason($season->getId())),
               'assigned' => count($this->getSeasonMapper()->getParticipantsBySeason($season->getId())),
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        //@todo: validate forwarding. is league still editable, has season not yet startet ?
        $id = (int) $this->params()->fromRoute('id', 1);

        //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        $no = $this->getLeagueMapper()->getNewLeagueNoBySeason($season->getId());

        $league = new League();
        $league->setNumber($no);
        $league->setSeason($season);

        $form = $this->getLeagueForm();
        $form->bindEntity($league);

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
                $this->getLeagueMapper()->save($league);

                foreach ($object->getPlayers() as $participant) {
                    /* @var $participant \Season\Entity\Participant */
                    $participant->setLeague($league);
                    $this->getLeagueMapper()->save($participant);
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

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function showAction()
    {

        $associationId = (int) $this->params()->fromRoute('id', 1);

        //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($associationId)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($associationId);
        $leagues = $this->getLeagueMapper()->getLeagueInfoBySeason($season->getId());

        return new ViewModel(
            array(
                'season' => $season,
                'leagues' => $leagues
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        //@todo: validate forwarding. is league still editable, has season not yet startet ?
        $leagueId = (int) $this->params()->fromRoute('id', 0);

        $league = $this->getLeagueMapper()->getLeagueById($leagueId);
        //no new season! add season first
        if (is_null($league)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $form = $this->getLeagueForm();
        $form->bindEntity($league);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('createLeague', array('action' => 'show'));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $object = $form->getData();

                foreach ($object->getPlayers() as $participant) {
                    /* @var $participant \Season\Entity\Participant */
                    $participant->setLeague($league);
                    $this->getLeagueMapper()->save($participant);
                }
                foreach ($object->getRemovePlayers() as $participant) {
                    /* @var $participant \Season\Entity\Participant */
                    $participant->setLeague(null);
                    $this->getLeagueMapper()->save($participant);
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

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function emptyAction()
    {

        //todo: remove all league from season with no players assigned during schedule macth making
        $id = (int) $this->params()->fromRoute('id', 1);

        //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        $unassignedLeagues = $this->getLeagueMapper()->getEmptyLeaguesBySeason($season->getId());

        return new ViewModel(
            array(
                'season' => $season,
                'unassigned' => $unassignedLeagues,
            )
        );
    }
}
