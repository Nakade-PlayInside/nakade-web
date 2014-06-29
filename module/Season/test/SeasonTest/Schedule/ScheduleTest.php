<?php
namespace Season\Schedule;

use League\Statistics\Results;
use PHPUnit_Framework_TestCase;

/**
 * Class ScheduleTest
 *
 * @package Season\Schedule
 */
class ScheduleTest extends PHPUnit_Framework_TestCase
{

    private $matchDates;
    private $object;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->matchDates =  array (1,2,3);
        $this->object = new Schedule($this->matchDates);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * testConstruct
     */
    public function testConstructor()
    {

        $this->assertSame($this->getMatchDates(), $this->getObject()->getMatchDates());
    }


    public function testChangeColors()
    {
        $test = array(1, 2);

        $this->getObject()->changeColors($test);
        $this->assertSame(2, $test[0]);
    }

    public function testMakePairingsForMatchDay()
    {
        $test = array(1,2,3,4,5,6,7,8);
        $res = $this->getObject()->makePairingsForMatchDay($test);
        var_dump($res);

    }

    /**
     * @return array
     */
    public function getMatchDates()
    {
        return $this->matchDates;
    }

    /**
     * @return Schedule
     */
    public function getObject()
    {
        return $this->object;
    }



}