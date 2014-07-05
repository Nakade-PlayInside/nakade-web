<?php
namespace Season\Controller;

use Season\Entity\Season;
use Season\Services\SeasonFormService;
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


        //new season! first play it before adding a new one. you can edit, of course
        if ($mapper->hasNewSeasonByAssociation($id)) {
           return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $association  = $mapper->getAssociationById($id);
        $season = new Season();
        $season->setAssociation($association);
        $season->setNumber(1);

        $lastSeason = $mapper->getLastSeasonByAssociation($id);
        if (!is_null($lastSeason)) {
            $data = $lastSeason->getArrayCopy();
            $season->exchangeArray($data);
            $no = $lastSeason->getNumber() + 1;
            $season->setNumber($no);
            $lastMatchDate = $mapper->getLastMatchDateOfSeason($lastSeason->getId());
            $newStartDate = clone $lastMatchDate;
            $newStartDate->modify('+2 week');
            $now = new \DateTime();
            if ($now > $newStartDate) {
                $newStartDate = $now;
            }
            $season->setStartDate($newStartDate);
        }

        /* @var $form \Season\Form\SeasonForm */
        $form = $this->getForm(SeasonFormService::SEASON_FORM);
        $form->bindEntity($season);

       if ($this->getRequest()->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
            $form->setData($postData);

            if ($form->isValid()) {

                $season = $form->getData();
                $mapper->save($season);

                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
       }

       return new ViewModel(array(
             'form' => $form,
           )
       );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $season = $mapper->getNewSeasonByAssociation($id);

        /* @var $form \Season\Form\SeasonForm */
        $form = $this->getForm(SeasonFormService::SEASON_FORM);
        $form->bindEntity($season);

        if ($this->getRequest()->isPost()) {
            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();

            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
            $form->setData($postData);

            if ($form->isValid()) {

                $season = $form->getData();
                $time = $season->getTime();
                $mapper->update($time);
                $mapper->update($season);

                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
        }


        return new ViewModel(
            array(
                'form' => $form,
            )
        );
    }

    /**
     * @return ViewModel
     */
    public function showRulesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $repository \Season\Mapper\SeasonMapper */
        $repository = $this->getRepository()->getMapper('season');

        /* @var $season \Season\Entity\Season */
        $season = $repository->getActiveSeasonByAssociation($id);


        return new ViewModel(
            array(
                'season' => $season,
            )
        );
    }

}
