<?php

namespace Application\Controller;

use Application\Services\ContactFormFactory;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class ContactController
 *
 * @package Application\Controller
 */
class ContactController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $form = $this->getForm(ContactFormFactory::CONTACT_FORM);
        $form->init();

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData =  $request->getPost();

            $form->setData($postData);

            if ($form->isValid()) {

                /* @var $contact \Application\Entity\Contact */
                $contact = $form->getData();

                /* @var $mail \Application\Mail\ContactMail */
                $mail = $this->getMailService()->getMail('contact');
                $mail->setContact($contact);
                $mail->sendMail($contact);

                $this->redirect()->toRoute('contact', array(
                    'action' => 'success'
                ));

            }
        }


        return array(
            'form' => $form,
        );
    }

    /**
     * @return ViewModel
     */
    public function successAction()
    {
        return new ViewModel();
    }
}
