<?php
namespace Moderator\Controller;

use Moderator\Entity\SupportMessage;
use Moderator\Entity\SupportRequest;
use Moderator\Services\FormService;
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

                $support = $form->getData();
//todo: mail for LM
                /* @var $mail \User\Mail\RegistrationMail */
                //    $mail = $this->getMailService()->getMail(MailService::REGISTRATION_MAIL);
                //    $mail->setUser($user);
                //    $mail->sendMail($user);

                $this->getMapper()->save($support);
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

                $message = $form->getData();
//todo: mail for LM
                /* @var $mail \Moderator\Mail\ReplyInfoMail */
             /*   $mail = $this->getMailService()->getMail(MailService::REPLY_INFO_MAIL);
                $mail->setSupportRequest($message->getRequest());
                $mail->sendMail($message->getRequest()->getRequester());
             */

                $this->getMapper()->save($message);
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

}
