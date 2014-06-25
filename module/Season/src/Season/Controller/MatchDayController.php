<?php
namespace Season\Controller;

use Season\Entity\Schedule;
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

        //todo: showing all match days
        return new ViewModel(
            array(
               'players' => null, //$this->getService()->getPlayers(),
               'schedule'=> null, //$this->getService()
                                 //->getSchedule(null),
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

                $data = $form->getData();
                //$this->getService()->addSchedule($data);

                return $this->redirect()->toRoute('createSeason');
            }
        }

        return new ViewModel(
            array(
              'form' => $form,
            )
        );
    }

}
