<?php
namespace Season\Controller;

use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * creating a new season
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class SeasonController extends AbstractController
{
    /**
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {
        //parameter set to one by default

        /* @var $repository \Season\Mapper\SeasonMapper */
        $repository = $this->getRepository()->getMapper('season');
        $res = $repository->getActualSeasonByTitle(1);
        var_dump($res);

        return new ViewModel(
            array(
              'actual'    => $this->getService()->getActualSeason(),
              'status'    => $this->getService()->getActualStatus(),
              'new'       => $this->getService()->getNewSeason(),
              'newstatus' => $this->getService()->getNewSeasonStatus(),
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $actual = $this->getService()->getActualSeason();
        $actual->setNumber($actual->getNumber()+1);
        $form = $this->getForm('season');
        $form->bindEntity($actual);

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
                $this->getService()->addSeason($data);

                return $this->redirect()->toRoute('newseason');
            }
       }


        return new ViewModel(
            array(
              'form' => $form,
            )
        );
    }

}
