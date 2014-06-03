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

        // hasResult
        $appointment->setIsConfirmed(false);
        $match = new Match();
        $match->setResultId(1);
        $appointment->setMatch($match);
        $this->assertFalse($obj->isValidLink($confirmString, $appointment));

        //isConfirmedByLink
        $match->setResultId(null);
        $appointment->setMatch($match);
        $appointment->setConfirmString('test');
        $this->assertFalse($obj->isValidLink($confirmString, $appointment));

        $appointment->setConfirmString($confirmString);
        $this->assertTrue($obj->isValidLink($confirmString, $appointment));

    }

    /**
     * test isValidMatch
     */
    public function testIsValidMatch()
    {
        $obj = $this->getObj();

        $black = $this->getUserById(1);
        $white = $this->getUserById(2);
        $invalid = $this->getUserById(3);
        $match = $this->getMatchByPairing($black, $white);
        $match->setResultId(1);

        // match isNUll
        $this->assertFalse($obj->isValidMatch($black, null));

        // match has result
        $this->assertFalse($obj->isValidMatch($black, $match));

        // match hasAppointment
        $mock = $this->getRepositoryMock(false);
        $obj->setRepository($mock);
        $this->assertFalse($obj->isValidMatch($black, $match));

        // match has InvalidUser
        $match->setResultId(null);
        $mock = $this->getRepositoryMock(true);
        $obj->setRepository($mock);
        $this->assertFalse($obj->isValidMatch($invalid, $match));

        // match has validUser
        $mock = $this->getRepositoryMock(false);
        $obj->setRepository($mock);
        $this->assertTrue($obj->isValidMatch($black, $match));

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
        $match->setResultId(1);

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
        $match->setResultId(null);
        $appointment->setMatch($match);
        $this->assertFalse($obj->isValidConfirm($invalid, $appointment));

        // everyThing ok
        $this->assertTrue($obj->isValidConfirm($user, $appointment));

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


