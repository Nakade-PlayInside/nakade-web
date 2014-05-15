<?php

namespace Application\Controller;

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
        $form = $this->getForm('contact');



        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData =  $request->getPost();

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData();
                print_r($form->getData()); //for debug
                var_dump(strlen($data['subject']));
                //$responder->sendMail($appointment->getResponder());


            }
        }


        return array(
            'form' => $form,
        );
    }


}
