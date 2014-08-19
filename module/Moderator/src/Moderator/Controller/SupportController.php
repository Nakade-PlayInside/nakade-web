<?php
namespace Moderator\Controller;

use Moderator\Entity\SupportMessage;
use Moderator\Entity\SupportRequest;
use Moderator\Services\FormService;
use Moderator\Services\MailService;
use Zend\View\Model\ViewModel;

/**
 * Class ManagerController
 *
 * @package Moderator\Controller
 */
class SupportController extends DefaultController
{
    const HOME = 'support';

    /**
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $userId = $this->identity()->getId();

        return new ViewModel(
            array(
                'tickets' => $this->getMapper()->getSupportRequestsByUser($userId),
            )
        );
    }

    /**
     *
     * @return array|ViewModel
     */
    public function detailAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);

        return new ViewModel(
            array(
                'ticket' => $this->getMapper()->getTicketById($ticketId),
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $type = (int) $this->params()->fromRoute('id', self::ADMIN_TICKET);
        $type = $this->getTypeById($type);
        $creator = $this->getUserById($this->identity()->getId());

        $support = new SupportRequest($type, $creator);

        /* @var $form \Moderator\Form\SupportForm */
        $form = $this->getForm(FormService::SUPPORT_FORM);
        $form->bindEntity($support);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute(self::HOME);
            }

            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $support \Moderator\Entity\SupportRequest */
                $support = $form->getData();
                $this->getMapper()->save($support);
                $this->sendTicketMail($support);

                $this->flashMessenger()->addSuccessMessage('New Support Request');
                return $this->redirect()->toRoute(self::HOME);
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }

        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     *
     * @return array|ViewModel
     */
    public function mailAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->setTicketState($ticketId, self::STAGE_IN_PROCESS);
        $author = $this->getUserById($this->identity()->getId());

        $message = new SupportMessage($ticket, $author);

        /* @var $form \Moderator\Form\MailForm */
        $form = $this->getForm(FormService::MAIL_FORM);
        $form->bindEntity($message);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute(self::HOME, array('action' => 'detail', 'id' => $ticketId));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                /* @var $message \Moderator\Entity\SupportMessage */
                $message = $form->getData();
                $this->getMapper()->save($message);
                $this->sendTicketMail($message->getRequest());

                $this->flashMessenger()->addSuccessMessage('Replied To Request');
                return $this->redirect()->toRoute(self::HOME);
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }

        }

        return new ViewModel(
            array(
                'form'    => $form
            )
        );
    }

    /**
     *
     * @return ViewModel
     */
    public function cancelAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->setTicketState($ticketId, self::STAGE_CANCELED);

        if (!is_null($ticket)) {
            $this->flashMessenger()->addSuccessMessage('Ticket canceled.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute(self::HOME);
    }

    /**
     * @param SupportRequest $ticket
     */
    private function sendTicketMail(SupportRequest $ticket)
    {
        $manager = array();

        switch ($ticket->getType()->getId()) {
            case self::LEAGUE_MANAGER_TICKET:
                $associationId = $ticket->getAssociation()->getId();
                $manager = $this->getMapper()->getTicketManagerByAssociation($associationId);
                break;

            case self::ADMIN_TICKET:
                $manager = $this->getMapper()->getAdministrators();
                break;

            case self::REFEREE_TICKET:
                //todo: referee
                break;
        }

        /* @var $mail \Moderator\Mail\TicketMail */
        $mail = $this->getMailService()->getMail(MailService::TICKET_MAIL);
        $mail->setSupportRequest($ticket);

        /* @var $user \User\Entity\User */
        foreach ($manager as $user) {
            $mail->sendMail($user);
        }
    }

}
