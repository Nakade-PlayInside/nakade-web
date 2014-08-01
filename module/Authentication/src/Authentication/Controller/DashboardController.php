<?php
namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class DashboardController
 *
 * @package Authentication\Controller
 */
class DashboardController extends AbstractActionController
{
    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {
        $messageWidget  = $this->forward()
            ->dispatch('/Message/Controller/Message', array('action' => 'info'));


        $scheduleWidget  = $this->forward()
            ->dispatch('/League/Controller/TimeTable', array('action' => 'mySchedule'));

        $appointmentWidget  = $this->forward()
            ->dispatch('/Appointment/Controller/Show', array('action' => 'message'));

        $participationWidget  = $this->forward()
            ->dispatch('/Season/Controller/Season', array('action' => 'show'));

        $inviteWidget  = $this->forward()
            ->dispatch('/User/Controller/Profile', array('action' => 'invite'));

        $page = new ViewModel(array());
        $page->addChild($participationWidget, 'participationWidget');
        $page->addChild($messageWidget, 'messageWidget');
        $page->addChild($appointmentWidget, 'appointmentWidget');
        $page->addChild($scheduleWidget, 'scheduleWidget');

        //open beta until end of year
        if (date('c') < date('c', strtotime('12/31/2014'))) {
            $page->addChild($inviteWidget, 'inviteWidget');
        }

        return $page;
    }

}