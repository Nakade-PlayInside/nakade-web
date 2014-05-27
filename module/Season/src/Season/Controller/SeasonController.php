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
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $repository \Season\Mapper\SeasonMapper */
        $repository = $this->getRepository()->getMapper('season');
        $season = $repository->getNewSeasonByAssociation($id);
        $info = $repository->getSeasonInfo($season->getId());
        $season->exchangeArray($info);


        return new ViewModel(
            array(
                'season' => $season,
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');
        //is new season existing

        $now = new \DateTime();
        $minDate = clone $now;
        $startDate = clone $now;
        $season = new Season();
        $number = 1;

        $last = $mapper->getLastSeasonByAssociation($id);
        $last = $mapper->getSeasonById(7);

        if (is_null($last)) {
            $association  = $mapper->getAssociationById(1);
            $season->setAssociation($association);
            $mapper->getAssociationById(1);
        } else {

            $lastMatchDate = $mapper->getLastMatchDateOfSeason($last->getId());
            $minDate = clone $lastMatchDate;
            $startDate = clone $lastMatchDate;

            if ($lastMatchDate > $now) {
                $minDate = clone $lastMatchDate;
                $startDate = clone $lastMatchDate;
            }
            $season->exchangeArray($last->getArrayCopy());
            $number = $last->getNumber() + 1;
        }
        $startDate->modify('+2 week');

        $season->setNumber($number);
        $season->setStartDate($startDate);

        /* @var $form \Season\Form\SeasonForm */
        $form = $this->getForm('season');
        $form->setMinDate($minDate);
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
