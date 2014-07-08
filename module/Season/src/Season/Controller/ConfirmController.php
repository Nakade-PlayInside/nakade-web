<?php
namespace Season\Controller;

use Season\Services\RepositoryService;
use Zend\View\Model\ViewModel;
use Nakade\Abstracts\AbstractController;

/**
 * Confirm of an invitation by using link provided by email
 *
 * @package Season\Controller
 */
class ConfirmController extends AbstractController
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

        /* @var $repo \Season\Mapper\SeasonMapper */
       $repo = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);
       $participant = $repo->getParticipantById($id);

       if (is_null($participant) || 0 != strcmp($participant->getAcceptString(), $confirmString) || $participant->hasAccepted()) {
           return $this->redirect()->toRoute('playerConfirm', array('action' => 'failure'));
       }

       $participant->setHasAccepted(true);
       $repo->save($participant);

       return $this->redirect()->toRoute('playerConfirm', array('action' => 'success'));

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
