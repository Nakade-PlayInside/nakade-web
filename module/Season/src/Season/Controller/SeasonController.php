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
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');
        $last = $mapper->getLastSeasonByAssociation($id);
        $data = $mapper->getSeasonInfo($last->getId());

        $start = \DateTime::createFromFormat('Y-m-d H:i:s', $data['lastMatchDate']);

        $season = new Season();
        $season->exchangeArray($last->getArrayCopy());
        $season->setNumber($season->getNumber()+1);
        $season->setStartDate($start->modify('+2 week'));

        /* @var $form \Season\Form\SeasonForm */
        $form = $this->getForm('season');
        $form->setMinDate($start->format('Y-m-d'));
        $form->init();
        $form->bind($season);


       if ($this->getRequest()->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['button']['cancel']) {
                return $this->redirect()->toRoute('newseason', array('action' => 'create'));
            }
            $form->setData($postData);

            if ($form->isValid()) {

//                $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $season = $form->getData();
                $season->setIsReady(false);
                $mapper->save($season);

                return $this->redirect()->toRoute('newseason', array('action' => 'create'));
            }
       }


        return new ViewModel(
            array(
              'form' => $form,
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 4);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');
        $season = $mapper->getSeasonById($id);

        $lastMatchDate = $mapper->getLastMatchDateOfSeason(3);

        /* @var $form \Season\Form\SeasonForm */
        $form = $this->getForm('season');
        $form->setMinDate($lastMatchDate);
        $form->init();
        $form->bind($season);


        if ($this->getRequest()->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if ($postData['button']['cancel']) {
                return $this->redirect()->toRoute('newseason', array('action' => 'create'));
            }
            $form->setData($postData);

            if ($form->isValid()) {

                $season = $form->getData();
                $time = $season->getTime();
                $mapper->update($time);
                $mapper->update($season);

                return $this->redirect()->toRoute('newseason', array('action' => 'create'));
            }
        }


        return new ViewModel(
            array(
                'form' => $form,
            )
        );
    }

}
