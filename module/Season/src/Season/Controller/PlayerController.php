<?php
namespace Season\Controller;

use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Adding players to a league after creating season and league
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class PlayerController extends AbstractController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {

       return new ViewModel(
           array(
               'players' => $this->getService()->getPlayers(),

           )
       );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
       //make sure that there are leagues
       if (count($this->getService()->getLeaguesInSeason())==0) {
            $this->redirect()->toRoute('league/add');
       }

       $form = $this->getForm('player');

       if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if ($postData['cancel']) {
                return $this->redirect()->toRoute('season');
            }

            $form->setData($postData);

            if ($form->isValid()) {

                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $this->getService()->addPlayer($data);

                return $this->redirect()->toRoute('season');
            }
       }


        return new ViewModel(
            array(
              'form' => $form,
            )
        );
    }

}
