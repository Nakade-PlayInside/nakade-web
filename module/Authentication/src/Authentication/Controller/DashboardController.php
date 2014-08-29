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

        $participationWidget  = $this->forward()
            ->dispatch('/Season/Controller/Season', array('action' => 'show'));

        $inviteWidget  = $this->forward()
            ->dispatch('/User/Controller/Coupon', array('action' => 'invite'));

        $page = new ViewModel(array());
        $page->addChild($participationWidget, 'participationWidget');
        $page->addChild($scheduleWidget, 'scheduleWidget');

        //open beta until end of year
        if (date('c') < date('c', strtotime('12/31/2014'))) {
            $page->addChild($inviteWidget, 'inviteWidget');
        }

        return $page;
    }

}