<?php
namespace Season\Controller;

use Zend\View\Model\ViewModel;

/**
 * Class ScheduleController
 *
 * @package Season\Controller
 */
class ScheduleController extends DefaultController
{
    /**
    * @return array|ViewModel
    */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->isReady()) {
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
     * @return ViewModel
     */
    public function createAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->isReady()) {
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

                /* @var $schedule \Season\Schedule\Schedule */
                $schedule = $this->getService()->getSchedule();
                $schedule->getSchedule($season->getId());

                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }

        }

        return new ViewModel(
            array(
                'form' => $form,
                'season'=> $season,
            )
        );
    }

    /**
     * @return ViewModel
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        $season = $this->getSeasonMapper()->getNewSeasonByAssociation($id);
        if (is_null($season) || $season->isReady()) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $matches = $this->getSeasonMapper()->getMatchesBySeason($season->getId());

        /* @var $form \Season\Form\MatchDayForm */
        $form = $this->getConfirmForm();
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
            if (isset($postData['submit'])) {

                foreach ($matches as $match) {
                    $this->getSeasonMapper()->delete($match);
                }
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
        }


        return new ViewModel(
            array(
                'matches' => $matches,
                'season'=> $season,
                'form'=> $form,
            )
        );
    }

}
