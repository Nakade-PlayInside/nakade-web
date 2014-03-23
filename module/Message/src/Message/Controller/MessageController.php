<?php
namespace Message\Controller;

use Message\Entity\Delete;
use Message\Entity\Message;
use Nakade\Abstracts\AbstractController;
use Message\Form\MessageForm;
use Message\Form\ReplyForm;
use Zend\View\Model\ViewModel;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSeasonServiceFactory is needed to be set.
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class MessageController extends AbstractController
{

    /**
     * @return array|\Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if ($this->identity() === null) {
           return $this->redirect()->toRoute('login');
        }

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
        if ($this->identity() === null) {
            return $this->redirect()->toRoute('login');
        }

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
        if ($this->identity() === null) {
           return $this->redirect()->toRoute('login');
        }

        $returnPath = $this->getRequest()->getHeader('referer')->uri()->getPath();
        $id  = (int) $this->params()->fromRoute('id', 0);


        /* @var $repo \Message\Mapper\MessageMapper */
        $repo = $this->getRepository()->getMapper('message');

        $messages = $repo->getAllMessagesById($id);
        $lastMessage =  $repo->getLastMessageById($id);

        //set read date if not set yet
        if (is_null($lastMessage->getReadDate())) {
            $lastMessage->setReadDate(new \DateTime());
            $repo->update($lastMessage);

        }

        return new ViewModel(
            array (
                 'returnPath' => $returnPath,
                'messages'  => $messages,
                'replyId'   => $lastMessage->getId(),
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function newAction()
    {

       if ($this->identity() === null) {
           return $this->redirect()->toRoute('login');
       }

       $id = $this->identity()->getId();

        /* @var $repo \Message\Mapper\MessageMapper */
       $repo = $this->getRepository()->getMapper('message');

       $recipients = $repo->getAllRecipients($id);

       $message = new Message();
       $message->setSender($id);

       $form = new MessageForm($recipients);
       $form->bindEntity($message);

       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
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

       if ($this->identity() === null) {
           return $this->redirect()->toRoute('login');
       }

       $id  = (int) $this->params()->fromRoute('id', 1);

        /* @var $repo \Message\Mapper\MessageMapper */
        $repo = $this->getRepository()->getMapper('message');

       /* @var $message \Message\Entity\Message */
       $message = $repo->getLastMessageById($id);

       //prepare
       $message->setSubject('Re:' . $message->getSubject());
       $name = $message->getSender()->getShortName();

       $form = new ReplyForm($name);
       $form->bindEntity($message);


       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('message');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $message = $form->getData();


                $threadId = $message->getId();
                if (!is_null($message->getThreadId())) {
                    $threadId = $message->getThreadId();
                }


                $sender = $message->getReceiver();
                $receiver = $message->getSender();
                $text = $message->getMessage();
                $subject=$message->getSubject();


                $reply = new Message();

                $reply->setThreadId($threadId);
                $reply->setReceiver($receiver);
                $reply->setSender($sender);
                $reply->setSubject($subject);
                $reply->setMessage($text);
                $reply->setSendDate(new \DateTime());


                $repo->save($reply);

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
        if ($this->identity() === null) {
            return $this->redirect()->toRoute('login');
        }
        $uid = $this->identity()->getId();
        $messageId  = (int) $this->params()->fromRoute('id', 1);


        $this->getRepository()->getMapper('message')->hideMessageByUser($uid, $messageId);

        return $this->redirect()->toRoute('message');
    }


}
