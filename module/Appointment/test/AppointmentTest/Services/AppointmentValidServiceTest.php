<?php
namespace Appointment\Services;

use Appointment\Entity\Appointment;
use Season\Entity\Match;
use PHPUnit_Framework_TestCase;
use User\Entity\User;

/**
 * Class AppointmentValidServiceTest
 *
 * @package Appointment\Services
 */
class AppointmentValidServiceTest extends PHPUnit_Framework_TestCase
{
    private $obj;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->obj = new AppointmentValidService();

        $mock = $this->getServiceLocatorMock();
        $this->obj->createService($mock);
    }

    /**
     * @return \Appointment\Services\AppointmentValidService
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     * test setter and getter
     */
    public function testPropertiesSettings()
    {
        $obj = $this->getObj();
        $mock = $this->getRepositoryMock();

        $obj->setRepository($mock);
        $this->assertSame($obj->getRepository(), $mock);
    }

        /**
     * test create Service
     */
    public function testCreateService()
    {
        $obj = $this->getObj();
        $this->assertInstanceOf('Appointment\Services\RepositoryService', $obj->getRepository());

    }

    /**
     * test isValidUser
     */
    public function testIsValidUser()
    {
        $black = $this->getUserById(1);
        $white = $this->getUserById(2);
        $match = $this->getMatchByPairing($black, $white);
        $invalidUser = $this->getUserById(3);

        $result = $this->invokeMethod('isValidUser', array($black, $match));
        $this->assertTrue($result);

        $result = $this->invokeMethod('isValidUser', array($white, $match));
        $this->assertTrue($result);

        $result = $this->invokeMethod('isValidUser', array($invalidUser, $match));
        $this->assertFalse($result);
    }

    /**
     * test isProcessed
     */
    public function testIsProcessed()
    {
        $appointment = new Appointment();

        $result = $this->invokeMethod('isProcessed', array($appointment));
        $this->assertFalse($result);

        $appointment->setIsConfirmed(true);
        $appointment->setIsRejected(false);
        $result = $this->invokeMethod('isProcessed', array($appointment));
        $this->assertTrue($result);

        $appointment->setIsConfirmed(false);
        $appointment->setIsRejected(true);
        $result = $this->invokeMethod('isProcessed', array($appointment));
        $this->assertTrue($result);

        $appointment->setIsConfirmed(false);
        $appointment->setIsRejected(false);
        $result = $this->invokeMethod('isProcessed', array($appointment));
        $this->assertFalse($result);
    }

    /**
     * test hasAppointment
     */
    public function testHasAppointment()
    {
        $obj = $this->getObj();
        $match = new Match();

        $mock = $this->getRepositoryMock(false);
        $obj->setRepository($mock);

        $result = $this->invokeMethod('hasAppointment', array($match));
        $this->assertFalse($result);

        $mock = $this->getRepositoryMock(true);
        $obj->setRepository($mock);

        $result = $this->invokeMethod('hasAppointment', array($match));
        $this->assertTrue($result);

    }

    /**
     * test isConfirmedByLink
     */
    public function testIsConfirmedByLink()
    {
        $appointment = new Appointment();

        $confirmString = 'bla';
        $result = $this->invokeMethod('isConfirmedByLink', array($appointment, $confirmString));
        $this->assertFalse($result);

        $appointment->setConfirmString($confirmString);
        $result = $this->invokeMethod('isConfirmedByLink', array($appointment, $confirmString));
        $this->assertTrue($result);

        $result = $this->invokeMethod('isConfirmedByLink', array($appointment, 'test'));
        $this->assertFalse($result);
    }

    /**
     * test isValidLink
     */
    public function testIsValidLink()
    {
        $appointment = new Appointment();
        $obj = $this->getObj();

        $confirmString = 'bla';

        // appointment isNUll
        $this->assertFalse($obj->isValidLink($confirmString));

        // isProcessed
        $appointment->setIsConfirmed(true);
        $this->assertFalse($obj->isValidLink($confirmString, $appointment));

        //hasResult ->invalid
        $appointment->setIsConfirmed(false);
        $match = new Match();
        $result = $this->getResultMock();
        $match->setResult($result);
        $appointment->setMatch($match);
        $this->assertFalse($obj->isValidLink($confirmString, $appointment));

        //isConfirmedByLink
        $match = new Match();

        $appointment->setMatch($match);
        $appointment->setConfirmString('test');
        $this->assertFalse($obj->isValidLink($confirmString, $appointment));

        $appointment->setConfirmString($confirmString);
        $this->assertTrue($obj->isValidLink($confirmString, $appointment));

    }

    /**
     * testIsValid by null == match
     */
    public function  testHasResult()
    {
        $result = $this->invokeMethod('hasResult', array(null));
        $this->assertFalse($result);

        $match = new Match();
        $result = $this->invokeMethod('hasResult', array($match));
        $this->assertFalse($result);

        $result = $this->getResultMock();
        $match->setResult($result);
        $result = $this->invokeMethod('hasResult', array($match));
        $this->assertTrue($result);

    }


    /**
     * test isValidMatch
     */
    public function testIsInvalidMatch()
    {
        $obj = $this->getObj();

        $black = $this->getUserById(1);
        $white = $this->getUserById(2);
        $invalid = $this->getUserById(3);
        $match = $this->getMatchByPairing($black, $white);
        $result = $this->getResultMock(1);
        $match->setResult($result);

        $this->assertFalse($obj->isValidMatch($black, null));

        // match has result
        $this->assertFalse($obj->isValidMatch($black, $match));

        // match hasAppointment
        $mock = $this->getRepositoryMock(false);
        $obj->setRepository($mock);
        $this->assertFalse($obj->isValidMatch($black, $match));

        // match has InvalidUser
        $result = $this->getResultMock();
        $match->setResult($result);
        $mock = $this->getRepositoryMock(true);
        $obj->setRepository($mock);
        $this->assertFalse($obj->isValidMatch($invalid, $match));
    }

    /**
     * test isValidMatch
     */
    public function testIsValidMatch()
    {
        $obj = $this->getObj();

        $black = $this->getUserById(1);
        $white = $this->getUserById(2);
        $match = $this->getMatchByPairing($black, $white);

        $this->assertTrue($obj->isValidMatch($black, $match));

        $match = $this->getMatchByPairing($white, $black);
        $this->assertTrue($obj->isValidMatch($black, $match));

    }


    /**
     * testIsValidResponder
     */
    public function testIsValidResponder()
    {
        $appointment = new Appointment();
        $user = $this->getUserById(1);
        $appointment->setResponder($user);
        $result = $this->invokeMethod('isValidResponder', array($user, $appointment));

        $this->assertTrue($result);

        $invalidUser = $this->getUserById(3);
        $result = $this->invokeMethod('isValidResponder', array($invalidUser, $appointment));

        $this->assertFalse($result);
    }

      /**
     * test isValidMatch
     */
    public function testIsValidConfirm()
    {
        $obj = $this->getObj();

        $appointment = new Appointment();
        $user = $this->getUserById(1);
        $invalid = $this->getUserById(3);
        $appointment->setResponder($user);
        $match = new Match();
        $result = $this->getResultMock(1);
        $match->setResult($result);

        // appointment isNUll
        $this->assertFalse($obj->isValidConfirm($user, null));

        // match has been processed
        $appointment->setIsConfirmed(true);
        $this->assertFalse($obj->isValidConfirm($user, $appointment));

        // match has result
        $appointment->setIsConfirmed(false);
        $appointment->setMatch($match);
        $this->assertFalse($obj->isValidConfirm($user, $appointment));

        //  is not responder
        $match = new Match();
        $result = $this->getResultMock();
        $match->setResult($result);
        $appointment->setMatch($match);

        $this->assertFalse($obj->isValidConfirm($invalid, $appointment));


        // everyThing ok
        $appointment = new Appointment();
        $match = new Match();
        $user = $this->getUserById(1);
        $appointment->setResponder($user);
        $appointment->setMatch($match);

        $this->assertTrue($obj->isValidConfirm($user, $appointment));

    }


    /**
     * @param int|null $id
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getResultMock($id=null)
    {
        $mock = $this
            ->getMockBuilder('League\Entity\Result')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));


        return $mock;
    }

    // testing private methods
    /**
     * for testing private and protected methods by reflection. Do this for complex methods
     *
     * @param string $methodName
     * @param array  $parameters
     *
     * @return mixed
     */
    private function invokeMethod($methodName, array $parameters = array())
    {
        $object = $this->getObj();
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param int $uid
     *
     * @return User
     */
    private function getUserById($uid)
    {
        $user = new User();
        $user->setId($uid);
        return $user;
    }

    /**
     * @param User $black
     * @param User $white
     *
     * @return Match
     */
    private function getMatchByPairing(User $black, User $white)
    {
        $match = new Match();
        $match->setBlack($black);
        $match->setWhite($white);
        return $match;
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
            ->with('Appointment\Services\RepositoryService')
            ->will($this->returnValue($this->getRepositoryMock()));


        return $mock;
    }

    /**
     * @param bool $hasAppointment
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getRepositoryMock($hasAppointment=false)
    {
        $mock = $this->getServiceMock('Appointment\Services\RepositoryService');

        $mapper = $this->getMapperMock($hasAppointment);
        $mock
            ->expects($this->any())
            ->method('getMapper')
            ->will($this->returnValue($mapper));

        return $mock;

    }

    /**
     * @param bool $hasAppointment
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getMapperMock($hasAppointment)
    {
        $mock = $this->getServiceMock('\Appointment\Mapper\AppointmentMapper');

        $result = array();
        if ($hasAppointment) {
           $result[]='something';
        }

        $mock
            ->expects($this->any())
            ->method('getAppointmentByMatch')
            ->will($this->returnValue($result));

        return $mock;

    }

    /**
     * @param mixed $service
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getServiceMock($service)
    {
        return $this
            ->getMockBuilder($service)
            ->disableOriginalConstructor()
            ->getMock();
    }

}


