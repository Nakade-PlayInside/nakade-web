<?php
namespace Mail\Services;

use PHPUnit_Framework_TestCase;


class MailMessageFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * test setter and getter
     */
    public function testPropertiesSetting()
    {
        $obj = new MailMessageFactory();

        $obj ->setFrom('me@there.gov');
        $this->assertSame('me@there.gov', $obj->getFrom());

        $obj ->setReply('we@there.gov');
        $this->assertSame('we@there.gov', $obj->getReply());

        $obj ->setFromName('me');
        $this->assertSame('me', $obj->getFromName());

        $obj ->setReplyName('we');
        $this->assertSame('we', $obj->getReplyName());

        $obj ->setSubject('see me');
        $this->assertSame('see me', $obj->getSubject());

        $obj ->setBody('we can mail this');
        $this->assertSame('we can mail this', $obj->getBody());

        $obj->setTo("my@where.org");
        $this->assertSame("my@where.org", $obj->getTo());

        $obj->setToName("Patrick");
        $this->assertSame("Patrick", $obj->getToName());

    }

    /**
     * test Message method
     */
    public function testGetMessage()
    {
        $obj = $this->getFactoryWithAllRequiredProperties();
        $this->assertInstanceOf('Zend\Mail\Message', $obj->getMessage());
    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testGetMessageExceptionByNoSubject()
    {
        $obj = $this->getFactoryWithAllRequiredProperties();
        $obj->setSubject(null);

        $obj->getMessage();
    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testGetMessageExceptionByNoBody()
    {
        $obj = $this->getFactoryWithAllRequiredProperties();
        $obj->setBody(null);

        $obj->getMessage();
    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testGetMessageExceptionByNoTo()
    {
        $obj = $this->getFactoryWithAllRequiredProperties();
        $obj->setTo(null);

        $obj->getMessage();
    }

    /**
     * test with well-known data
     */
    public function testCreateService()
    {
        $obj = new MailMessageFactory();
        $mock = $this->getServiceMock($this->getConfig());
        $obj->createService($mock);

        $this->assertInstanceOf('Mail\Services\MailMessageFactory', $obj->createService($mock));
    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testCreateServiceExceptionByNoMessage()
    {
        $obj = new MailMessageFactory();

        $config = array('nakade_mail' => array());
        $mock = $this->getServiceMock($config);
        $obj->createService($mock);

    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testCreateServiceExceptionByNoFrom()
    {
        $obj = new MailMessageFactory();

        $config = array('nakade_mail' => array('message' => array('reply' => 'test')));
        $mock = $this->getServiceMock($config);
        $obj->createService($mock);
    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testCreateServiceExceptionByNoReply()
    {
        $obj = new MailMessageFactory();

        $config = array('nakade_mail' => array('message' => array('from' => 'me')));
        $mock = $this->getServiceMock($config);
        $obj->createService($mock);
    }

    private function getFactoryWithAllRequiredProperties()
    {
        $obj = new MailMessageFactory();
        $obj->setFrom('none@somewhere.org');
        $obj->setReply('noReply@somewhere.org');
        $obj->setSubject('test');
        $obj->setBody('my mail text');
        $obj->setTo('you@somewhere.org');

        return $obj;
    }


    /**
     * @return array
     */
    private function getConfig()
    {

        return array(
            'nakade_mail' => array(

                'message' => array(
                    'from' => 'name@domain.org' ,
                    'name' => 'Susan Miller',
                    'reply'=> 'noReply@domain.org',
                    'replyName'=> 'Sam Nobody',
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


