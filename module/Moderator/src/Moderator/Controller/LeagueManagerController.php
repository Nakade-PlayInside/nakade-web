<?php
namespace Moderator\Controller;

use Moderator\Entity\StageInterface;
use Moderator\Entity\SupportMessage;
use Moderator\Entity\SupportRequest;
use Moderator\Pagination\TicketPagination;
use Moderator\Services\FormService;
use Moderator\Services\MailService;
use Moderator\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class LeagueManagerController
 *
 * @package Moderator\Controller
 */
class LeagueManagerController extends AbstractController implements StageInterface
{
    /**
     *
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        //todo: show only tickets for associations bound to the LM
        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $pagination = new TicketPagination($entityManager);
        $offset = (TicketPagination::ITEMS_PER_PAGE * ($page -1));

        return new ViewModel(
            array(
                'supportRequests' => $this->getMapper()->getSupportRequestsByPages($offset),
                'paginator' => $pagination->getPagination($page),
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
     *
     * @return array|ViewModel
     */
    public function mailAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->getMapper()->getTicketById($ticketId);

        /* @var $form \Moderator\Form\MailForm */
        $form = $this->getForm(FormService::MAIL_FORM);
        $message = new SupportMessage();
        $stage = $this->getStageById(self::STAGE_WAITING);
        $ticket->setStage($stage);
        $message->setRequest($ticket);

        $form->bindEntity($message);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('leagueManager', array('action' => 'detail', 'id' => $ticketId));
            }

            $form->setData($postData);
            if ($form->isValid()) {

               $message = $form->getData();

                /* @var $mail \Moderator\Mail\ReplyInfoMail */
                $mail = $this->getMailService()->getMail(MailService::REPLY_INFO_MAIL);
                $mail->setSupportRequest($message->getRequest());
                $mail->sendMail($message->getRequest()->getRequester());

                $this->getMapper()->save($message);
                $this->flashMessenger()->addSuccessMessage('New Support Reply Message');

                return $this->redirect()->toRoute('leagueManager');
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
    public function doneAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->getMapper()->getTicketById($ticketId);

        if (!is_null($ticket)) {
            $stage = $this->getStageById(self::STAGE_DONE);
            $ticket->setStage($stage);
            $ticket->setDoneDate(new \DateTime());
            $this->getMapper()->save($ticket);

            $this->sendStageMail($ticket);

            $this->flashMessenger()->addSuccessMessage('Ticket done.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('leagueManager');
    }

    /**
     *
     * @return array|ViewModel
     */
    public function acceptAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->getMapper()->getTicketById($ticketId);

        if (!is_null($ticket)) {
            $stage = $this->getStageById(self::STAGE_IN_PROCESS);
            $ticket->setStage($stage);
            $ticket->setStartDate(new \DateTime());
            $this->getMapper()->save($ticket);

            $this->sendStageMail($ticket);
            $this->flashMessenger()->addSuccessMessage('Ticket accepted.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('leagueManager');
    }

    /**
     *
     * @return array|ViewModel
     */
    public function cancelAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->getMapper()->getTicketById($ticketId);

        if (!is_null($ticket)) {
            $stage = $this->getStageById(self::STAGE_CANCELED);
            $ticket->setStage($stage);
            $this->getMapper()->save($ticket);

            $this->sendStageMail($ticket);
            $this->flashMessenger()->addSuccessMessage('Ticket canceled.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('leagueManager');
    }

    /**
     *
     * @return array|ViewModel
     */
    public function reopenAction()
    {
        $ticketId = (int) $this->params()->fromRoute('id', 0);
        $ticket = $this->getMapper()->getTicketById($ticketId);

        if (!is_null($ticket)) {
            $stage = $this->getStageById(self::STAGE_REOPENED);
            $ticket->setStage($stage);
            $this->getMapper()->save($ticket);

            $this->sendStageMail($ticket);
            $this->flashMessenger()->addSuccessMessage('Ticket reopened.');
        } else {
            $this->flashMessenger()->addSuccessMessage('Input Error');
        }

        return $this->redirect()->toRoute('leagueManager');
    }


    /**
     * @return \Moderator\Mapper\ManagerMapper
     */
    private function getMapper()
    {
        /* @var $repo \Moderator\Services\RepositoryService */
        $repo = $this->getRepository();
        return $repo->getMapper(RepositoryService::MANAGER_MAPPER);
    }

    /**
     * @param int $stageId
     *
     * @return \Moderator\Entity\SupportStage
     */
    private function getStageById($stageId)
    {
        return $this->getMapper()->getEntityManager()->getReference('Moderator\Entity\SupportStage', intval($stageId));
    }

    /**
     * @param SupportRequest $ticket
     */
    private function sendStageMail(SupportRequest $ticket)
    {
        $mail = $this->getMailService()->getMail(MailService::STAGE_CHANGED_MAIL);
        $mail->setSupportRequest($ticket);
        $mail->sendMail($ticket->getRequester());
    }

}
