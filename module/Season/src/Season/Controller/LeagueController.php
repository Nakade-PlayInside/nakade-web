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

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->hasMatchDays()) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

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
    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->hasMatchDays()) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

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
    public function editAction()
    {
        $leagueId = (int) $this->params()->fromRoute('id', 0);
        $league = $this->getLeagueMapper()->getLeagueById($leagueId);

        if (is_null($league)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $season = $league->getSeason();;
        $matchDays = $this->getSeasonMapper()->getMatchDaysBySeason($season->getId());
        if (!empty($matchDays)) {
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
                return $this->redirect()->toRoute('createLeague');
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

}
