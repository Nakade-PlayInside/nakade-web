<?php
namespace Season\Controller;

use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Creating a schedule of the newly created season
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class ScheduleController extends AbstractController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {

        return new ViewModel(
            array(
               'players' => null, //$this->getService()->getPlayers(),
               'schedule'=> null, //$this->getService()
                                 //->getSchedule(null),
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {

       $form = $this->getForm('schedule');

       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('newseason');
            }

            $form->setData($postData);
            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $this->getService()->addSchedule($data);

                return $this->redirect()->toRoute('schedule');
            }
       }


        return new ViewModel(
            array(
              'form' => $form,
            )
        );
    }

}
