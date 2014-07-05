<?php
namespace Season\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;
use Season\Services\SeasonFormService;
use Season\Services\RepositoryService;

/**
 * Class SeasonController
 *
 * @package Season\Controller
 */
class ScheduleController extends AbstractController
{
    /**
    * @return array|ViewModel
    */
    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);

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
     * @return ViewModel
     */
    public function createAction()
    {
        $id = (int) $this->params()->fromRoute('id', 1);

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }

        $season = $mapper->getNewSeasonByAssociation($id);
        $form = $this->getForm(SeasonFormService::CREATE_FORM);

        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('configMatchDay');
            }

            if (isset($postData['create'])) {

                /* @var $schedule \Season\Schedule\Schedule */
                $schedule = $this->getService()->getSchedule();
                $schedule->getSchedule($season->getId());

                return $this->redirect()->toRoute('createSeason');
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

        /* @var $mapper \Season\Mapper\SeasonMapper */
        $mapper = $this->getRepository()->getMapper(RepositoryService::SEASON_MAPPER);

        //no new season! add season first
        if (!$mapper->hasNewSeasonByAssociation($id)) {
            return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
        }
        $season = $mapper->getNewSeasonByAssociation($id);
        $matches = $mapper->getMatchesBySeason($season->getId());

        /* @var $form \Season\Form\MatchDayForm */
        $form = $this->getForm(SeasonFormService::DELETE_FORM);
        if ($this->getRequest()->isPost()) {

            //get post data, set data to from, prepare for validation
            $postData =  $this->getRequest()->getPost();
            //cancel
            if (isset($postData['cancel'])) {
                return $this->redirect()->toRoute('createSeason', array('action' => 'create'));
            }
            if (isset($postData['delete'])) {

                foreach ($matches as $match) {
                    $mapper->delete($match);
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
