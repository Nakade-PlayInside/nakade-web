<?php
namespace Season\Controller;

use Season\Entity\MatchDay;
use Season\Schedule\ScheduleDates;
use Zend\View\Model\ViewModel;

/**
 * Class MatchDayController
 *
 * @package Season\Controller
 */
class MatchDayController extends DefaultController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->hasSchedule()) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        return new ViewModel(
            array(
               'matchDays' => $season->getMatchDays(),
               'season'=> $season,
            )
        );
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->hasSchedule()) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $schedule = $this->getService()->getScheduleEntity($season);

        $form = $this->getMatchDayConfigForm();
        $form->bindEntity($schedule);


        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }

            $form->setData($postData);
            if ($form->isValid()) {

                /* @var $schedule \Season\Entity\Schedule */
                $schedule = $form->getData();

                /* @var $seasonDates \Season\Entity\SeasonDates */
                $seasonDates = $schedule->getSeason()->getAssociation()->getSeasonDates();
                $seasonDates->exchangeArray($schedule->getArrayCopy());

                $this->getSeasonMapper()->save($seasonDates);

                $object = new ScheduleDates($schedule);
                $dates = $object->getScheduleDates();

                foreach ($dates as $round => $matchDate) {
                    $matchDay = new MatchDay();
                    $matchDay->setMatchDay($round);
                    $matchDay->setSeason($season);
                    $matchDay->setDate($matchDate);
                    $this->getSeasonMapper()->save($matchDay);
                }

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
        $id = (int) $this->params()->fromRoute('id', -1);

        $matchDay = $this->getSeasonMapper()->getMatchDayById($id);
        if (is_null($matchDay)) {
            return $this->redirect()->toRoute('configMatchDay');
        }

        $form = $this->getMatchDayForm();
        $form->bindEntity($matchDay);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['button']['cancel'])) {
                return $this->redirect()->toRoute('configMatchDay');
            }

            $form->setData($postData);
            if ($form->isValid()) {

                /* @var $matchDay \Season\Entity\MatchDay */
                $matchDay = $form->getData();
                $this->getSeasonMapper()->save($matchDay);

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
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->hasSchedule()) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $form = $this->getConfirmForm();
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
            if (isset($postData['submit'])) {

                foreach ($season->getMatchDays() as $matchDay) {
                    $this->getSeasonMapper()->delete($matchDay);
                }
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
        }


        return new ViewModel(
            array(
                'matchDays' => $season->getMatchDays(),
                'season'=> $season,
                'form'=> $form,
            )
        );

    }

}
