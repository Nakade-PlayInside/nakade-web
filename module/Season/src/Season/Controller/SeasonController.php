<?php
namespace Season\Controller;

use Season\Entity\Season;
use Zend\View\Model\ViewModel;

/**
 * Class SeasonController
 *
 * @package Season\Controller
 */
class SeasonController extends DefaultController
{
    /**
    * @return array|ViewModel
    */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $season \Season\Entity\Season */
        $season = $this->getSeasonMapper()->getActiveSeasonByAssociation($id);
        $info = $this->getSeasonMapper()->getSeasonInfo($season->getId());
        $season->exchangeArray($info);

        return new ViewModel(
            array(
              'season' => $season,
            )
        );
    }

    /**
     * widget for new season invitation
     *
     * @return array|ViewModel
     */
    public function showAction()
    {
        $userId = $this->identity()->getId();

        /* @var $season \Season\Entity\Season */
        $seasons = $this->getSeasonMapper()->getNewSeasonsByUser($userId);

        return new ViewModel(
            array(
                'newSeasons' => $seasons,
            )
        );
    }

    /**
     * @return ViewModel
     */
    public function createAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'add'));
        }

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        $info = $this->getSeasonMapper()->getSeasonInfo($season->getId());
        $season->exchangeArray($info);

        return new ViewModel(
            array(
                'season' => $season,
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        //new season! first play it before adding a new one. you can edit, of course
        if ($this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
           return $this->redirect()->toRoute('createSeason');
        }

        $association  = $this->getSeasonMapper()->getAssociationById($id);
        $season = new Season();
        $season->setAssociation($association);
        $season->setNumber(1);

        $lastSeason = $this->getSeasonMapper()->getLastSeasonByAssociation($id);
        if (!is_null($lastSeason)) {
            $data = $lastSeason->getArrayCopy();
            $season->exchangeArray($data);
            $no = $lastSeason->getNumber() + 1;
            $season->setNumber($no);
            $lastMatchDate = $this->getSeasonMapper()->getLastMatchDateOfSeason($lastSeason->getId());
            $newStartDate = clone $lastMatchDate;
            $newStartDate->modify('+2 week');
            $now = new \DateTime();
            if ($now > $newStartDate) {
                $newStartDate = $now;
            }
            $season->setStartDate($newStartDate);
        }

        $form = $this->getSeasonForm();
        $form->bindEntity($season);

       if ($this->getRequest()->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('createSeason');
            }
            $form->setData($postData);

            if ($form->isValid()) {

                $season = $form->getData();
                $this->getSeasonMapper()->save($season);

                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
       }

       return new ViewModel(array(
             'form' => $form,
           )
       );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);

        $form = $this->getSeasonForm();
        $form->bindEntity($season);

        if ($this->getRequest()->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
            $form->setData($postData);

            if ($form->isValid()) {

                $season = $form->getData();
                $time = $season->getTime();
                $this->getSeasonMapper()->update($time);
                $this->getSeasonMapper()->update($season);

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
     * @return ViewModel
     */
    public function activateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        //todo: send emails with match schedule to all participants
        //no new season! add season first
        if (!$this->getSeasonMapper()->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        $form = $this->getConfirmForm();

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }

            if (isset($postData['submit'])) {

                $season->setIsReady(true);
                $this->getSeasonMapper()->save($season);
                return $this->redirect()->toRoute('createSeason');
            }

        }

        return new ViewModel(
            array(
                'form' => $form,
                'season'=> $season,
            )
        );
    }



    /**
     * widget for time, extra time and komi
     *
     * @return ViewModel
     */
    public function showRulesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $season \Season\Entity\Season */
        $season = $this->getSeasonMapper()->getActiveSeasonByAssociation($id);


        return new ViewModel(
            array(
                'season' => $season,
            )
        );
    }

}
