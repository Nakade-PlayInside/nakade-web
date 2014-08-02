<?php
namespace Message\Controller;

use Message\Entity\Message;
use Message\Services\MessageFormService;
use Message\Services\RepositoryService;
use Nakade\Abstracts\AbstractController;
use Message\Form\MessageForm;
use Message\Form\ReplyForm;
use Zend\View\Model\ViewModel;

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
        $uid = $this->identity()->getId();
        $messages =  $this->getMessageMapper()->getInboxMessages($uid);

        return new ViewModel(
            array('messages' => $messages)
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

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData =  $request->getPost();
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('message');
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $message = $form->getData();
                $this->getMessageMapper()->save($message);

                /* @var $mail \Message\Mail\NotifyMail */
                $mail = $this->getMailService()->getMail('notify');
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

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function replyAction()
    {

        $uid = $this->identity()->getId();
        $messageId  = (int) $this->params()->fromRoute('id', -1);


        /* @var $repo \Message\Mapper\MessageMapper */
        $repo = $this->getRepository()->getMapper('message');

        $sender=$this->getMessageMapper()->getUserById($uid);
        $message=$this->getMessageMapper()->getMessageById($messageId);

        $subject = 'Re:' . $message->getSubject();
        $receiver = $message->getReceiver();
        if ($message->getReceiver()->getId() == $uid) {
            $receiver = $message->getSender();
        }

        $threadId = $message->getId();
        if (!is_null($message->getThreadId())) {
            $threadId = $message->getThreadId();
        }

        $reply = new Message();
        $reply->setSubject($subject);
        $reply->setThreadId($threadId);
        $reply->setSender($sender);
        $reply->setReceiver($receiver);


        $form = new ReplyForm($receiver->getShortName(), $this->getTranslator());
        $form->bindEntity($reply);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData = $request->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('message');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $reply = $form->getData();
                $reply->setSendDate(new \DateTime());
                $repo->save($reply);

                /* @var $mail \Message\Mail\NotifyMail */
                $mail = $this->getMailService()->getMail('notify');
                $mail->sendMail($receiver);

                return $this->redirect()->toRoute('message');
            }
        }

        return new ViewModel(
            array('form' => $form)
        );
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

}
