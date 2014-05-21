<?php
namespace Season\Controller;

use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * For adding leagues after creating a new season
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class LeagueController extends AbstractController
{

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {


       //better to get the last season
      // $actualSeason =  $this->getSeasonMapperService()->getActualSeason();
       //$league       =  $this->league()->getTopLeague($actualSeason);

       return new ViewModel(
           array(
              //'users' => $this->table()->getTable($league),
              //'nextGames' => $this->match()->getNextThreeGames($league),
           )
       );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
       $league = $this->getService()->getNewLeague();

       if (null===$league) {
            $this->redirect()->toRoute('newseason/add');
       }

       $form = $this->getForm('league');
       $form->bindEntity($league);

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
                $this->getService()->addLeague($data);

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
