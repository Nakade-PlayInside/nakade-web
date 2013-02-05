<?php

namespace PhlyContact\Controller;

use PhlyContact\Form\ContactForm;
use Zend\Mail\Transport;
use Zend\Mail\Message as Message;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController
{
    protected $_form;
    protected $_message;
    protected $_transport;

    public function setMessage(Message $message)
    {
        $this->_message = $message;
    }

    public function setMailTransport(Transport\TransportInterface $transport)
    {
        $this->_transport = $transport;
    }

    public function indexAction()
    {
        return array(
            'form' => $this->_form,
        );
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('contact');
        }

        $post = $this->request->getPost();
        $form = $this->_form;
        $form->setData($post);
        if (!$form->isValid()) {
            $model = new ViewModel(
                array(
                    'error' => true,
                    'form'  => $form,
                )
            );
            $model->setTemplate('phly-contact/contact/index');
            return $model;
        }

        // send email...
        $this->sendEmail($form->getData());

        return $this->redirect()->toRoute('contact/thank-you');
    }

    public function thankYouAction()
    {
        $headers = $this->request->getHeaders();
        if (!$headers->has('Referer')
           || !preg_match(
               '#/contact$#', $headers->get('Referer')->getFieldValue()
           )
        ) {
            return $this->redirect()->toRoute('contact');
        }

        return array();
    }

    public function setContactForm(ContactForm $form)
    {
        $this->_form = $form;
    }

    protected function sendEmail(array $data)
    {
        $from    = $data['from'];
        $subject = '[BBC] ' . $data['subject'];
        $body    = $data['body'];

        $this->_message->addFrom($from)
                      ->addReplyTo($from)
                      ->setSubject($subject)
                      ->setBody($body);
        $this->_transport->send($this->_message);
    }
}
