<?php
namespace Message\Controller;

use Message\Entity\Message;
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
        $messages =  $this->getRepository()
            ->getMapper('message')
            ->getInboxMessages($uid);

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

        /* @var $repo \Message\Mapper\MessageMapper */
        $repo = $this->getRepository()->getMapper('message');
        $messages =  $repo->getSentBoxMessages($uid);

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


        /* @var $repo \Message\Mapper\MessageMapper */
        $repo = $this->getRepository()->getMapper('message');

        $messages = $repo->getAllMessagesById($messageId);
        $lastMessage =  $repo->getMessageById($messageId);

        if ($lastMessage->getReceiver()->getId()==$uid && $lastMessage->isNew()) {
            $lastMessage->setNew(false);
            $lastMessage->setReadDate(new \DateTime());
            $repo->update($lastMessage);
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

       $id = $this->identity()->getId();

        /* @var $repo \Message\Mapper\MessageMapper */
       $repo = $this->getRepository()->getMapper('message');

       $recipients = $repo->getAllRecipients($id);

       $message = new Message();
       $message->setSender($id);


       $form = new MessageForm($recipients, $this->getTranslator());
       $form->bindEntity($message);

       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('message');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $message = $form->getData();

                //date
                $message->setSendDate(new \DateTime());

                $sender = $repo->getUserById($message->getSender());
                $message->setSender($sender);

                $recipient =  $repo->getUserById($message->getReceiver());
                $message->setReceiver($recipient);

                $repo->save($message);

                /* @var $mail \Message\Mail\NotifyMail */
                $mail = $this->getMailService()->getMail('notify');
                $mail->sendMail($recipient);

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
    public function replyAction()
    {

       $uid = $this->identity()->getId();
       $messageId  = (int) $this->params()->fromRoute('id', -1);


        /* @var $repo \Message\Mapper\MessageMapper */
        $repo = $this->getRepository()->getMapper('message');
        $sender=$repo->getUserById($uid);

        $message=$repo->getMessageById($messageId);

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


       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

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
        $this->getRepository()->getMapper('message')->hideMessageByUser($uid, $messageId);

        return $this->redirect()->toRoute('message');
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function infoAction()
    {
        $user = $this->identity();

        /* @var $mapper \Message\Mapper\MessageMapper */
        $mapper = $this->getRepository()->getMapper('message');
        $noNewMails = $mapper->getNumberOfNewMessages($user);

        return new ViewModel(
            array('noNewMails' => $noNewMails)
        );

    }

}
