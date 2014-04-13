<?php
namespace League\Controller;

use Nakade\Abstracts\AbstractController;
use Zend\View\Model\ViewModel;
use User\Entity\User;

/**
 * League tables and schedules of the actual season.
 * Top league table is presented by the default action index.
 * ActionSeasonServiceFactory is needed to be set.
 *
 * @author Holger Maerz <holger@nakade.de>
 */
class ActualSeasonController extends AbstractController
{

    /**
    * Default action showing up the Top League table
    * in a short and compact version. This can be used as a widget.
    *
    * @return array|ViewModel
    */
    public function indexAction()
    {

        return new ViewModel(
            array(
              'title'     => $this->getService()->getTitle(),
              'table'     => $this->getService()->getTopLeagueTable()

            )
        );
    }

    /**
    * Shows actual matchplan of a user and his results.
    * If user is not in  a league, the top league schedule
    * is shown.
    *
    * @return array|ViewModel
    */
    public function scheduleAction()
    {
       $uid = $this->getUserId();

       return new ViewModel(
           array(
              'title'   => $this->getService()->getScheduleTitle($uid),
              'matches' => $this->getService()->getSchedule($uid),
           )
       );
    }

    /**
     * @return ViewModel
     */
    public function myScheduleAction()
    {
        $uid = $this->getUserId();

        return new ViewModel(
            array(
                'userId' =>  $uid,
                'title'   => $this->getService()->getScheduleTitle($uid),
                'matches' => $this->getService()->getMySchedule($uid),
            )
        );
    }

    /**
    * Shows the user's league table. If user is not in a league, the
    * Top league is shown instead. The Table is sortable.
    *
    * @return array|ViewModel
    */
    public function tableAction()
    {

       $uid = $this->getUserId();

        //sorting the table
       $sorting  = $this->params()->fromRoute('sort', null);

       return new ViewModel(
           array(
              'table'       => $this->getService()
                                    ->getLeagueTable($uid, $sorting),
              'tiebreakers' => $this->getService()
                                    ->getTiebreakerNames(),
              'title'       => $this->getService()
                                    ->getTableTitle($uid),

           )
       );
    }

    /**
     * @return \Zend\Http\Response | int
     */
    private function getUserId()
    {
        $user = $this->identity();

        if (is_null($user)) {
            return $this->redirect()->toRoute('login');
        }

        return $user->getId();
    }

}
