<?php
namespace Season\Controller;

use Season\Entity\Season;
use Zend\Form\FormInterface;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class SeasonController
 *
 * @package Season\Controller
 */
class SeasonController extends AbstractController
{
    /**
    * @return array|ViewModel
    */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $repository \Season\Mapper\SeasonMapper */
        $repository = $this->getRepository()->getMapper('season');

        /* @var $season \Season\Entity\Season */
        $season = $repository->getActiveSeasonByAssociation($id);
        $info = $repository->getSeasonInfo($season->getId());
        $season->exchangeArray($info);

        return new ViewModel(
            array(
              'season' => $season,
            )
        );
    }

    /**
     * @return ViewModel
     */
    public function createAction()
    {
        $showWidget  = $this->forward()->dispatch('Season\Controller\Season', array('action' => 'index'));
        $page = new ViewModel();

        $page->addChild($showWidget, 'showWidget');

        return $page;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
      //  $actual = $this->getService()->getActualSeason();
      //  $actual->setNumber($actual->getNumber()+1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');
        $last = $mapper->getLastSeasonByAssociation(1);

        /* @var $form \Season\Form\SeasonForm */
        $form = $this->getForm('season');
        $form->bind($last);


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
                $season = $form->getData();
               // var_dump($data['tiebreaker1']);
                var_dump($season);
                var_dump($data); die;
               // $season->setTieBreaker1($em->getReference('TieBreaker', ID));
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
