<?php
namespace Season\Controller;

use Season\Entity\Participant;
use Zend\View\Model\ViewModel;

/**
 * Confirm of an invitation by using link provided by email
 *
 * @package Season\Controller
 */
class ConfirmController extends DefaultController
{
    /**
     * Verification action. A direct link to this action is provided
     * in the user's verification mail.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {

       $id   = $this->params()->fromQuery('id', null);
       $confirmString   = $this->params()->fromQuery('confirm', null);

       //no params -> error
       if (empty($id) || empty($confirmString)) {
           return $this->redirect()->toRoute('playerConfirm', array('action' => 'error'));
       }

       $participant = $this->getSeasonMapper()->getParticipantById($id);

       if (is_null($participant) || 0 != strcmp($participant->getAcceptString(), $confirmString) || $participant->hasAccepted()) {
           return $this->redirect()->toRoute('playerConfirm', array('action' => 'failure'));
       }

       $participant->setHasAccepted(true);
       $participant->setDate(new \DateTime());
       $this->getSeasonMapper()->save($participant);

       return $this->redirect()->toRoute('playerConfirm', array('action' => 'success'));

    }

    /**
     * register for new season by user
     *
     * @return ViewModel
     */
    public function registerAction()
    {
        $seasonId = (int) $this->params()->fromRoute('id', -1);
        $userId = $this->identity()->getId();

        $season = $this->getSeasonMapper()->getSeasonById($seasonId);
        if (empty($season) || $season->isReady() || $season->hasMatchDays()) {
            return $this->redirect()->toRoute('dashboard');
        }

        $form = $this->getConfirmForm();
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('dashboard');
            }

            if (isset($postData['submit'])) {
                $participant = $this->getSeasonMapper()->getParticipantByUserAndSeason($userId, $seasonId);

                if (is_null($participant)) {
                    $participant = new Participant();
                    $user = $this->getSeasonMapper()->getEntityManager()->getReference('\User\Entity\User', $userId);
                    $participant->setUser($user);
                    $participant->setSeason($season);
                }

                $participant->setDate(new \DateTime());
                $participant->setHasAccepted(true);
                $this->getSeasonMapper()->save($participant);

                return $this->redirect()->toRoute('dashboard');
            }

        }

        return new ViewModel(
            array(
                'form' => $form,
            )
        );
    }

    /**
     * Activation failed.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function successAction()
    {
        return new ViewModel();
    }

    /**
     * Activation failed.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function failureAction()
    {
        return new ViewModel();
    }

    /**
     * Data missing.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function errorAction()
    {
        return new ViewModel();
    }

}
