<?php
namespace Message\Controller;

use Nakade\Abstracts\AbstractController;
use Message\Form\MessageForm;
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
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.  
    */
    public function indexAction()
    {
        if($this->identity() === null) {
           return $this->redirect()->toRoute('login');
        }
        
        $messages = $this->getService()->getAllMessages();
        return new ViewModel(
           array('messages'      => $messages)
        );
    }
    
    public function showAction()
    {
        if($this->identity() === null) {
           return $this->redirect()->toRoute('login');
        }
        
        $id  = (int) $this->params()->fromRoute('id', 0);
        $message = $this->getService()->getMessage($id);
        return new ViewModel(
           array(
              //'title'     => $this->getService()->getTitle(),
                'message'  => $message,
           )
        );
    }
    
    public function newAction()
    {
       
       if($this->identity() === null) {
           return $this->redirect()->toRoute('login');
       }
      
       $id = $this->identity()->getId();
       $recipients = $this->getService()->getAllRecipients($id);
       
       $message = new \Message\Entity\Message();
       $message->setSender($id);
       
       $form = new MessageForm($recipients);
       $form->bindEntity($message);
              
       if ($this->getRequest()->isPost()) {
            
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            
            //cancel
            if($postData['cancel']) {
                return $this->redirect()->toRoute('message');
            }
            
            $form->setData($postData);
           
            if ($form->isValid()) {
           
                $message = $form->getData();
                
                //date
                $message->setSendDate(new \DateTime());
                
                $sender = $this->getService()
                    ->getUserById($message->getSender());
                //sender
                $message->setSender($sender);
                
                $recipient = $this->getService()
                    ->getUserById($message->getReceiver());
                //receiver
                $message->setReceiver($recipient);
                
                $this->getService()->getMapper('message')->save($message);
                
                return $this->redirect()->toRoute('message');
            }
        }

        
        return new ViewModel(
           array('form' => $form)
        );
    }
    
    
}
