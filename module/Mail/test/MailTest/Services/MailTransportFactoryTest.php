<?php
namespace Mail\Services;

use PHPUnit_Framework_TestCase;

/**
 * Class MailTransportFactoryTest
 *
 * @package Mail\Services
 */
class MailTransportFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    private function getSmtpOptions()
    {
        return array(
            'host'             => 'smtp.gmail.com',
            'port'             => 587,
            'connectionClass'  => 'login',
            'connectionConfig' => array(
                'ssl'      => 'tls',
                'username' => 'contact@your.tld',
                'password' => 'password',
            )
        );
    }

    /**
     * test setter and getter
     */
    public function testPropertiesSetting()
    {
        $obj = new MailTransportFactory();

        $obj ->setOptions($this->getSmtpOptions());
        $this->assertSame($this->getSmtpOptions(), $obj->getOptions());

    }

    /**
     * test setter and getter
     */
    public function testGetTransport()
    {
        $obj = new MailTransportFactory();
        $this->assertInstanceOf('Zend\Mail\Transport\Sendmail', $obj->getTransport(MailTransportFactory::SENDMAIL));

        $obj->setOptions($this->getSmtpOptions());
        $this->assertInstanceOf('Zend\Mail\Transport\Smtp', $obj->getTransport(MailTransportFactory::SMTP));

        $obj->setOptions(array('path' => '/tmp'));
        $this->assertInstanceOf('Zend\Mail\Transport\File', $obj->getTransport(MailTransportFactory::FILE));

    }

    /**
     * @expectedException     \DomainException
     */
    public function testGetTransportException()
    {
        $obj = new MailTransportFactory();
        $obj->getTransport('unknown');
    }

    /**
     * test with well-known data
     */
    public function testCreateService()
    {
        $obj = new MailTransportFactory();
        $mock = $this->getServiceMock($this->getConfig());
        $obj->createService($mock);

        $this->assertInstanceOf('Zend\Mail\Transport\Smtp', $obj->createService($mock));
    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testRuntimeExceptionByNoTransport()
    {
        $obj = new MailTransportFactory();

        $config = array('nakade_mail' => array());
        $mock = $this->getServiceMock($config);
        $obj->createService($mock);

    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testRuntimeExceptionByNoMethod()
    {
        $obj = new MailTransportFactory();

        $config = array('nakade_mail' => array('transport' => array()));
        $mock = $this->getServiceMock($config);
        $obj->createService($mock);

    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testRuntimeExceptionByNoOptions()
    {
        $obj = new MailTransportFactory();

        $config = array('nakade_mail' => array('transport' => array('method' => 'smtp')));
        $mock = $this->getServiceMock($config);
        $obj->createService($mock);

    }

    /**
     * @return array
     */
    private function getConfig()
    {
        $options = $this->getSmtpOptions();

        return array(
            'nakade_mail' => array(
                'transport' => array(
                    'method'   => 'smtp',
                    'options' => $options
                ),
            )
        );
    }

    /**
     * @param array $config
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getServiceMock(array $config)
    {
        $mock = $this
            ->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('get')
            ->with('config')
            ->will($this->returnValue($config));

        return $mock;
    }


}


