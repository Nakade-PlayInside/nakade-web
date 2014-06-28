<?php
namespace Season\Controller;

use Season\Entity\MatchDay;
use Season\Entity\Schedule;
use Season\Schedule\ScheduleDates;
use Season\Services\SeasonFormService;
use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;

/**
 * Class MatchDayController
 *
 * @package Season\Controller
 */
class MatchDayController extends AbstractController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $season = $mapper->getNewSeasonByAssociation($id);
        $matchDays = $mapper->getMatchDaysBySeason($season->getId());

        return new ViewModel(
            array(
               'matchDays' => $matchDays,
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

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);
        $noOfMatchDays = $mapper->getNoOfMatchDaysBySeason($season->getId());

        $schedule = new Schedule($season, $noOfMatchDays);

        /* @var $form \Season\Form\MatchDayForm */
        $form = $this->getForm(SeasonFormService::MATCH_DAY_FORM);
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

                $mapper->save($seasonDates);

                $object = new ScheduleDates($schedule);
                $dates = $object->getScheduleDates();

                var_dump($dates);die;
                $round = 1;
                foreach ($dates as $matchDate) {
                    $matchDay = new MatchDay();
                    $matchDay->setMatchDay($round);
                    $matchDay->setSeason($season);
                    $matchDay->setDate($matchDate);
                    $round++;
                    $mapper->save($matchDay);
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
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper('season');

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        //todo: get MatchDay by ID

        /* @var $form \Season\Form\MatchDayForm */
        $form = $this->getForm(SeasonFormService::MATCH_DAY_FORM);
       // $form->bindEntity($schedule);


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

                $mapper->save($seasonDates);

                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
        }

        return new ViewModel(
            array(
                'form' => $form,
            )
        );
    }


}
