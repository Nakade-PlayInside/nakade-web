<?php
namespace Appointment\Mail;
include_once 'MailTestHelper.php';
use PHPUnit_Framework_TestCase;
/**
 * Class MailMessageFactoryTest
 *
 * @package Mail\Services
 */
class ConfirmMailTest extends PHPUnit_Framework_TestCase
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
        $this->obj = new ConfirmMail($helper->getMessageSrv(), $helper->getTransportSrv());
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
    public function  testPropertiesSettings()
    {
        $obj = $this->getObj();

        $this->assertSame(MailTestHelper::URL, $obj->getUrl());
        $this->assertSame(MailTestHelper::TIME, $obj->getTime());
        $this->assertSame($this->appointment, $obj->getAppointment());

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
        $this->assertContains(MailTestHelper::MATCH_INFO, $message);
    }

}


