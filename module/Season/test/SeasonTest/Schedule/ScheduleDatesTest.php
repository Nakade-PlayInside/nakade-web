<?php
namespace Season\Schedule;

use PHPUnit_Framework_TestCase;

/**
 * Class ScheduleTest
 *
 * @package Season\Schedule
 */
class ScheduleDatesTest extends PHPUnit_Framework_TestCase
{
    private $object;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->object = new HarmonicLeaguePairing();
    }

    /**
     * @return \Season\Entity\Schedule
     */
    private function getScheduleMock($cycle=7)
    {
        $mock = $this
            ->getMockBuilder('Season\Entity\Schedule')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getNoOfMatchDays')
            ->will($this->returnValue(5));

        $date = new \DateTime("2015-01-02 11:15:00");
        $mock
            ->expects($this->any())
            ->method('getDate')
            ->will($this->returnValue($date));

        $mock
            ->expects($this->any())
            ->method('getDay')
            ->will($this->returnValue(5));

        $mock
            ->expects($this->any())
            ->method('getCycle')
            ->will($this->returnValue($cycle));

        $mock
            ->expects($this->any())
            ->method('getTimeAsString')
            ->will($this->returnValue('18:30:15'));

        return $mock;

    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $object     Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod($object, $methodName, array $parameters)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * changeColors
     */
    public function testInit()
    {

        $schedule = $this->getScheduleMock();
        $obj = new ScheduleDates($schedule);

        $this->assertSame(
            $schedule,
            $obj->getSchedule(),
            sprintf("Expected object not found.")
        );

    }


    /**
     * @dataProvider provideCycles
     */
    public function testGetScheduleDates($cycle, $expected)
    {

        $schedule = $this->getScheduleMock($cycle);
        $obj = new ScheduleDates($schedule);

        $this->assertSame(
            json_encode($expected),
            json_encode($obj->getScheduleDates()),
            sprintf("Expected Date not found.")
        );

    }

    /**
     * @return array
     */
    public function provideCycles()
    {
        return array(
            array(
                1, array(
                    1=> new \DateTime("2015-01-02 18:30:15"),
                    2=> new \DateTime("2015-01-03 18:30:15"),
                    3=> new \DateTime("2015-01-04 18:30:15"),
                    4=> new \DateTime("2015-01-05 18:30:15"),
                    5=> new \DateTime("2015-01-06 18:30:15"),
                )
            ),//next day
            array(
                5, array(
                    1=> new \DateTime("2015-01-02 18:30:15"),
                    2=> new \DateTime("2015-01-05 18:30:15"),
                    3=> new \DateTime("2015-01-06 18:30:15"),
                    4=> new \DateTime("2015-01-07 18:30:15"),
                    5=> new \DateTime("2015-01-08 18:30:15"),
                )
            ),//next weekday
            array(
                7, array(
                    1=> new \DateTime("2015-01-02 18:30:15"),
                    2=> new \DateTime("2015-01-09 18:30:15"),
                    3=> new \DateTime("2015-01-16 18:30:15"),
                    4=> new \DateTime("2015-01-23 18:30:15"),
                    5=> new \DateTime("2015-01-30 18:30:15"),
                )
            ),//next week

            array(
                14, array(
                    1=> new \DateTime("2015-01-02 18:30:15"),
                    2=> new \DateTime("2015-01-16 18:30:15"),
                    3=> new \DateTime("2015-01-30 18:30:15"),
                    4=> new \DateTime("2015-02-13 18:30:15"),
                    5=> new \DateTime("2015-02-27 18:30:15"),
                )
            ),//next fortnight
            array(
                21, array(
                    1=> new \DateTime("2015-01-02 18:30:15"),
                    2=> new \DateTime("2015-01-23 18:30:15"),
                    3=> new \DateTime("2015-02-13 18:30:15"),
                    4=> new \DateTime("2015-03-06 18:30:15"),
                    5=> new \DateTime("2015-03-27 18:30:15"),
                )
            ),//+3 weeks
            array(
                30, array(
                    1=> new \DateTime("2015-01-02 18:30:15"),
                    2=> new \DateTime("2015-01-30 18:30:15"),
                    3=> new \DateTime("2015-02-27 18:30:15"),
                    4=> new \DateTime("2015-03-27 18:30:15"),
                    5=> new \DateTime("2015-04-24 18:30:15"),
                )
            ),//+4 weeks
            array(
                20, array(
                    1=> new \DateTime("2015-01-02 18:30:15"),
                    2=> new \DateTime("2015-01-22 18:30:15"),
                    3=> new \DateTime("2015-02-11 18:30:15"),
                    4=> new \DateTime("2015-03-03 18:30:15"),
                    5=> new \DateTime("2015-03-23 18:30:15"),
                )
            ),//+20 days

        );
    }


    /**
     * @return HarmonicLeaguePairing
     */
    public function getObject()
    {
        return $this->object;
    }



}