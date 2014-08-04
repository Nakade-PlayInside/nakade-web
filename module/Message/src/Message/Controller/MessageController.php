<?php
namespace Message\Controller;

use Message\Entity\Message;
use Message\Pagination\MessagePagination;
use Message\Services\MailService;
use Message\Services\MessageFormService;
use Message\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;
use \Zend\Http\Request;
use \Zend\Form\Form;

/**
 * Class MessageController
 *
 * @package Message\Controller
 */
class MessageController extends AbstractController
{

    /**
     * @return array|\Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $page = (int) $this->params()->fromRoute('id', 1);

        $uid = $this->identity()->getId();
        $messages =  $this->getMessageMapper()->getInboxMessages($uid);
        //todo: refactor pagination service
        $myPagination = new MessagePagination(count($messages));
        $offset = (MessagePagination::ITEMS_PER_PAGE * ($page -1));//value for mapper request

        return new ViewModel(array(
            'messages' => $this->getMessageMapper()->getUserMessagesByPages($uid, $offset),
            'paginator' =>   $myPagination->getPagination($page),
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function sentAction()
    {
        $uid = $this->identity()->getId();
        $messages = $this->getMessageMapper()->getSentBoxMessages($uid);

        return new ViewModel(
            array('messages' => $messages)
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function showAction()
    {
        //todo: paging messages; new message as icon btn; post ausgang als btn
        $returnPath = $this->getRequest()->getHeader('referer')->uri()->getPath();

        $messageId  = (int) $this->params()->fromRoute('id', -1);
        $uid = $this->identity()->getId();

        $messages = $this->getMessageMapper()->getAllMessagesById($messageId);
        $lastMessage =  $this->getMessageMapper()->getMessageById($messageId);

        if ($lastMessage->getReceiver()->getId()==$uid && $lastMessage->isNew()) {
            $lastMessage->setNew(false);
            $lastMessage->setReadDate(new \DateTime());
            $this->getMessageMapper()->update($lastMessage);
        }

        return new ViewModel(
            array (
                'returnPath' => $returnPath,
                'messages'  => $messages,
                'replyId'   => $messageId,
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function newAction()
    {

        /* @var $form \Message\Form\MessageForm */
        $form = $this->getForm(MessageFormService::MESSAGE_FORM);
        $message = new Message();
        $form->bindEntity($message);
        //todo: moderator message
        return $this->makeMessage($this->getRequest(), $form);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function replyAction()
    {
        $messageId  = (int) $this->params()->fromRoute('id', -1);
        $message = $this->getMessageMapper()->getMessageById($messageId);

        /* @var $form \Message\Form\ReplyForm */
        $form = $this->getForm(MessageFormService::REPLY_FORM);
        $form->bindEntity($message);

        return $this->makeMessage($this->getRequest(), $form);
    }


    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function deleteAction()
    {
        $uid = $this->identity()->getId();
        $messageId  = (int) $this->params()->fromRoute('id', -1);
        $this->getMessageMapper()->hideMessageByUser($uid, $messageId);

        return $this->redirect()->toRoute('message');
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function infoAction()
    {
        $user = $this->identity();
        $noNewMails = $this->getMessageMapper()->getNumberOfNewMessages($user);

        return new ViewModel(
            array('noNewMails' => $noNewMails)
        );

    }

    /**
     * @return \Message\Mapper\MessageMapper
     */
    public function getMessageMapper()
    {
        return $this->getRepository()->getMapper(RepositoryService::MESSAGE_MAPPER);
    }

    /**
     * @param Request $request
     * @param Form $form
     *
     * @return \Zend\Http\Response
     */
    private function makeMessage(Request $request, Form $form)
    {
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData = $request->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('message');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $message \Message\Entity\Message */
                $message = $form->getData();
                $this->getMessageMapper()->save($message);

                /* @var $mail \Message\Mail\NotifyMail */
                $mail = $this->getMailService()->getMail(MailService::NOTIFY_MAIL);
                $mail->setMessage($message);
                $mail->sendMail($message->getReceiver());

                $this->flashMessenger()->addSuccessMessage('Message send');
                return $this->redirect()->toRoute('message');
            } else {
                $this->flashMessenger()->addErrorMessage('Input Error');
            }
        }

        return new ViewModel(
            array('form' => $form)
        );
    }


}
