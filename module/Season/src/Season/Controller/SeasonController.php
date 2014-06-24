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


        //new season! first play it before adding a new one. you can edit, of course
        if ($mapper->hasNewSeasonByAssociation($id)) {
           return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }


        $now = new \DateTime();
        $minDate = clone $now;
        $startDate = clone $now;
        $season = new Season();

        $last = $mapper->getLastSeasonByAssociation($id);

        if (is_null($last)) {
            $association  = $mapper->getAssociationById($id);
            $season->setAssociation($association);
        } else {

            $lastMatchDate = $mapper->getLastMatchDateOfSeason($last->getId());
            $minDate = clone $lastMatchDate;
            $startDate = clone $lastMatchDate;

            if ($lastMatchDate > $now) {
                $minDate = clone $lastMatchDate;
                $startDate = clone $lastMatchDate;
            }
            $season->exchangeArray($last->getArrayCopy());
            $season->setNumber($last->getNumber() + 1);
        }
        $startDate->modify('+2 week');
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
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $season = $mapper->getNewSeasonByAssociation($id);
        $now = new \DateTime();

        $minDate = $season->getStartDate();
        if ($season->getStartDate() > $now) {
            $last = $mapper->getLastSeasonByAssociation($id);
            if (!is_null($last)) {
                $lastMatchDate = $mapper->getLastMatchDateOfSeason($last->getId());
                if ($lastMatchDate > $now) {
                    $minDate = $lastMatchDate;
                } else {
                    $minDate = $now;
                }
            }
        }

        //if startDate is in the future, now is the min date, otherwise min is the season's start date

        /* @var $form \Season\Form\SeasonForm */
        $form = $this->getForm('season');
        $form->setMinDate($minDate);
        $form->init();
        $form->bind($season);


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
