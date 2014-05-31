<?php
namespace Season\Controller;

use Season\Entity\League;
use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;
use \Season\Services\RepositoryService;
use \Season\Services\SeasonFormService;

/**
 * For adding leagues after creating a new season
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class LeagueController extends AbstractController
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
            return $this->redirect()->toRoute('season', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);

        /* @var $playerMapper \Season\Mapper\ParticipantMapper */
        $playerMapper = $this->getRepository()->getMapper(RepositoryService::PARTICIPANT_MAPPER);
        $noLeagues = $mapper->getNoOfLeaguesInSeason($season->getId());
        $season->setNoLeagues($noLeagues);

        return new ViewModel(
            array(
               'season' => $season,
               'accepted' => count($playerMapper->getAcceptingUsersBySeason($season->getId())),
               'assigned' => count($playerMapper->getParticipantsBySeason($season->getId())),
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

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('season', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);

        /* @var $form \Season\Form\ParticipantForm */
        $form = $this->getForm(SeasonFormService::LEAGUE_FORM);
        $form->setSeason($season);
        $form->init();

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('season', array('action' => 'create'));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);

                $league = new League();
                $league->setSeason($season);
                $league->setNumber($data['leagueNumber']);
                $mapper->save($league);

                foreach ($data['addPlayer'] as $playerId) {
                    /* @var $player \Season\Entity\Participant */
                    $player = $mapper->getEntityManager()->getReference('Season\Entity\Participant', $playerId);
                    $player->setLeague($league);
                    $mapper->save($player);

                }

                return $this->redirect()->toRoute('season', array('action' => 'create'));
            }
        }


        return new ViewModel(
            array(
                'form' => $form,
            )
        );
    }

    public function showAction()
    {
        //todo: show table with all leagues to edit
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        //todo: assign more players to a league, unassign players; leagueId is needed
        //@todo: validate forwarding. is league still editable, has season not yet startet ?
        $leagueId = (int) $this->params()->fromRoute('id', 0);

        /* @var $mapper \Season\Mapper\LeagueMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        $league = $mapper->getLeagueById($leagueId);
        //no new season! add season first
        if (is_null($league)) {
            return $this->redirect()->toRoute('season', array('action' => 'create'));
        }

        /* @var $form \Season\Form\LeagueForm */
        $form = $this->getForm(SeasonFormService::LEAGUE_FORM);
        $form->setLeague($league);
        $form->init();

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();
            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('season', array('action' => 'create'));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                foreach ($data['addPlayer'] as $playerId) {
                    /* @var $player \Season\Entity\Participant */
                    $player = $mapper->getEntityManager()->getReference('Season\Entity\Participant', $playerId);
                    $player->setLeague($league);
                    $mapper->save($player);
                }
                foreach ($data['removePlayer'] as $playerId) {
                    /* @var $player \Season\Entity\Participant */
                    $player = $mapper->getEntityManager()->getReference('Season\Entity\Participant', $playerId);
                    $player->setLeague(null);
                    $mapper->save($player);
                }


                return $this->redirect()->toRoute('season', array('action' => 'create'));
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

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('season', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);

        /* @var $playerMapper \Season\Mapper\LeagueMapper */
        $playerMapper = $this->getRepository()->getMapper(RepositoryService::LEAGUE_MAPPER);
        $unassignedLeagues = $playerMapper->getEmptyLeaguesBySeason($season->getId());

        return new ViewModel(
            array(
                'season' => $season,
                'unassigned' => $unassignedLeagues,
            )
        );
    }
}
