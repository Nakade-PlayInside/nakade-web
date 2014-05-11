<?php
namespace Appointment\Mail;
include_once 'MailTestHelper.php';
use PHPUnit_Framework_TestCase;
/**
 * Class MailMessageFactoryTest
 *
 * @package Mail\Services
 */
class RejectMailTest extends PHPUnit_Framework_TestCase
{
    private $helper;
    private $obj;
    private $appointment;

    /**
     * setUp
     */
    public function setUp()
    {

        $helper = new MailTestHelper();
        $this->helper = $helper;
        $this->obj = new RejectMail($helper->getMessageSrv(), $helper->getTransportSrv());
        $this->obj->setSignature($helper->getSignatureSrv());
        $this->obj->setUrl(MailTestHelper::URL);
        $this->obj->setTime(MailTestHelper::TIME);
        $this->appointment = $helper->getAppointment();
        $this->obj->setAppointment($this->appointment);

    }
    /**
     * @return \Appointment\Mail\AppointmentMail
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     * @return MailTestHelper
     */
    public function getHelper()
    {
        return $this->helper;
    }


    /**
     * test setter and getter
     */
    public function  testGetMailBody()
    {
        $obj = $this->getObj();
        $message = $obj->getMailBody();

        $this->assertContains(MailTestHelper::URL, $message);
        $this->assertContains($this->getHelper()->getNewDate()->format('d.m.y H:i'), $message);
        $this->assertContains($this->getHelper()->getOldDate()->format('d.m.y H:i'), $message);
        $this->assertContains(MailTestHelper::MATCH_INFO, $message);
    }

}


