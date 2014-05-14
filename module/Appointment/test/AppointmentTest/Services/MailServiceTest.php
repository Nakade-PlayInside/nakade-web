<?php
namespace Appointment\Services;

use PHPUnit_Framework_TestCase;

/**
 * Class MailMessageFactoryTest
 *
 * @package Mail\Services
 */
class MailServiceTest extends PHPUnit_Framework_TestCase
{
    private $obj;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->obj = new MailService();

        $mock = $this->getServiceLocatorMock();
        $this->obj->createService($mock);
    }

    /**
     * @return \Appointment\Services\MailService
     */
    public function getObj()
    {
        return $this->obj;
    }


    /**
     * test setter and getter
     */
    public function testCreateService()
    {
        $obj = $this->getObj();

        $this->assertInstanceOf('Zend\Mail\Transport\TransportInterface', $obj->getTransport());
        $this->assertInstanceOf('Mail\Services\MailMessageFactory', $obj->getMessage());
        $this->assertInstanceOf('Mail\Services\MailSignatureService', $obj->getSignature());
        $this->assertInstanceOf('Zend\I18n\Translator\Translator', $obj->getTranslator());

        $config = $this->getConfig();
        $this->assertSame($config['Appointment']['text_domain'], $obj->getTranslatorTextDomain());
        $this->assertSame($config['Appointment']['auto_confirm_time'], $obj->getConfirmTime());
        $this->assertSame($config['Appointment']['url'], $obj->getUrl());

    }

    /**
     * test getMail
     */
    public function testGetMail()
    {
        $this->assertInstanceOf('Appointment\Mail\ConfirmMail', $this->getObj()->getMail('confirm'));
        $this->assertInstanceOf('Appointment\Mail\SubmitterMail', $this->getObj()->getMail('submitter'));
        $this->assertInstanceOf('Appointment\Mail\RejectMail', $this->getObj()->getMail('reject'));
        $this->assertInstanceOf('Appointment\Mail\ResponderMail', $this->getObj()->getMail('responder'));

    }


    /**
     * @expectedException     \RuntimeException
     */
    public function testGetMailExceptionByUnknownType()
    {
        $this->getObj()->getMail('unknown');
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return array(
            'Appointment' => array(

                'text_domain' => 'Appointment',
                'auto_confirm_time' => '72',
                'url' => 'nakade.de',
             ),
         );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getServiceLocatorMock()
    {
        $mock = $this
            ->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('get')
            ->with($this->logicalOr(
                $this->equalTo('Mail\Services\MailTransportFactory'),
                $this->equalTo('Mail\Services\MailMessageFactory'),
                $this->equalTo('Mail\Services\MailSignatureService'),
                $this->equalTo('config'),
                $this->equalTo('translator')
            ))
            ->will($this->returnCallback(array($this, 'getServiceLocatorCallback')));


        return $mock;
    }

    /**
     * @param string $type
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getServiceLocatorCallback($type)
    {
        switch($type)
        {
            case 'translator':
                $mock = $this->getServiceMock('Zend\I18n\Translator\Translator');
                break;

            case 'Mail\Services\MailTransportFactory':
                $mock = $this->getServiceMock('Zend\Mail\Transport\TransportInterface');
                break;

            case 'config':
                $mock = $this->getConfig();
                break;

            default:
                $mock = $this->getServiceMock($type);
        }

        return $mock;
    }

    private function getServiceMock($service)
    {
        return $this
            ->getMockBuilder($service)
            ->disableOriginalConstructor()
            ->getMock();
    }

}


