<?php
namespace Appointment\Mail;

use Appointment\Entity\Appointment;
use PHPUnit_Framework_TestCase;
/**
 * Class MailMessageFactoryTest
 *
 * @package Mail\Services
 */
class MailTestHelper extends PHPUnit_Framework_TestCase
{
    protected $obj;
    protected $newDate;
    protected $oldDate;
    protected $transportSrv;
    protected $messageSrv;
    protected $signatureSrv;


    const URL = 'www.nakade.de';
    const TIME = '78';
    const MATCH_INFO = 'Shusaku - Go Seigen';
    const ID = 5;
    const CONFIRM_STRING = 'blaBLAganaterg';

    /**
     * setUp
     */
    public function __construct()
    {
        $this->transportSrv = $this->getServiceMock('Zend\Mail\Transport\TransportInterface');
        $this->messageSrv = $this->getServiceMock('Mail\Services\MailMessageFactory');
        $signature = $this->getServiceMock('Mail\Services\MailSignatureService');
        $signature->expects($this->any())->method('getSignatureText');

        $this->signatureSrv = $signature;
        $this->newDate = new \DateTime();
        $this->oldDate = $this->newDate->modify('-5 day');
    }


    public  function getAppointment()
    {
        $appointment = new Appointment();
        $appointment->setId(self::ID);
        $appointment->setConfirmString(self::CONFIRM_STRING);
        $appointment->setNewDate($this->getNewDate());
        $appointment->setOldDate($this->getOldDate());

        $match = $this->getMatchMock();
        $appointment->setMatch($match);
        return $appointment;

    }

    protected  function getMatchMock()
    {
        $mock = $this
            ->getMockBuilder('League\Entity\Match')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getMatchInfo')
            ->will($this->returnValue(self::MATCH_INFO));

        return $mock;

    }

    protected  function getServiceMock($service)
    {
        return $this
            ->getMockBuilder($service)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \DateTime
     */
    public function getNewDate()
    {
        return $this->newDate;
    }

    /**
     * @return \DateTime
     */
    public function getOldDate()
    {
        return $this->oldDate;
    }

    /**
     * @return \Mail\Services\MailMessageFactory
     */
    public function getMessageSrv()
    {
        return $this->messageSrv;
    }

    /**
     * @return \Mail\Services\MailSignatureService
     */
    public function getSignatureSrv()
    {
        return $this->signatureSrv;
    }

    /**
     * @return \Zend\Mail\Transport\TransportInterface
     */
    public function getTransportSrv()
    {
        return $this->transportSrv;
    }
}


