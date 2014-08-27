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
        $scheduleWidget  = $this->forward()
            ->dispatch('/League/Controller/TimeTable', array('action' => 'mySchedule'));

        //@veraltet
        $appointmentWidget  = $this->forward()
            ->dispatch('/Appointment/Controller/Show', array('action' => 'message'));

        $participationWidget  = $this->forward()
            ->dispatch('/Season/Controller/Season', array('action' => 'show'));

        $inviteWidget  = $this->forward()
            ->dispatch('/User/Controller/Coupon', array('action' => 'invite'));

        //@veraltet
        $inviteInfo  = $this->forward()
            ->dispatch('/User/Controller/Coupon', array('action' => 'info'));

        $page = new ViewModel(array());
        $page->addChild($participationWidget, 'participationWidget');
        $page->addChild($appointmentWidget, 'appointmentWidget');
        $page->addChild($scheduleWidget, 'scheduleWidget');

        //open beta until end of year
        if (date('c') < date('c', strtotime('12/31/2014'))) {
            $page->addChild($inviteWidget, 'inviteWidget');
            $page->addChild($inviteInfo, 'inviteInfo');
        }

        return $page;
    }

}