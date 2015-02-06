<?php
namespace LeagueTest;

use League\Form\Hydrator\ResultValidator;
use Nakade\Result\ResultInterface;
use League\Entity\Result;
use League\Entity\ResultType;
use PHPUnit_Framework_TestCase;

class ResultValidatorTest extends PHPUnit_Framework_TestCase implements ResultInterface
{

    /**
     * testing init
     */
    public function testInit()
    {
        $em = $this->getEntityManagerMock();
        $obj = new ResultValidator($em);

        $this->assertSame(
            $em,
            $obj->getEntityManager(),
            sprintf("Expected value not found.")
        );
    }

    /**
     * testing return values
     */
    public function testValidBooleanReturn()
    {
        $em = $this->getEntityManagerMock();
        $obj = new ResultValidator($em);
        $result = $this->getResultMock(10.0, self::BYPOINTS);

        $this->assertTrue(
            $obj->validate($result),
            sprintf("Expected result not found.")
        );

        $result = $this->getResultMock(10.0, self::DRAW);

        $this->assertFalse(
            $obj->validate($result),
            sprintf("Expected result not found.")
        );
    }

    /**
     * @dataProvider resultProvider
     */
    public function testResultTypeExchangeByPoints($points, $expected)
    {
        $em = $this->getEntityManagerMock();
        $obj = new ResultValidator($em);
        $result = $this->getResultMock($points, self::BYPOINTS);

        $obj->validate($result);

        $this->assertSame(
            $expected,
            $result->getResultType()->getId(),
            sprintf("Expected result not found.")
        );
    }

    /**
    * providing several results for testing
    *
    * @return array
    */
    public function resultProvider()
    {
        return array(
            array(21.0, self::RESIGNATION),
            array(67.5, self::RESIGNATION),
            array(10.5, self::BYPOINTS),
            array(0.5, self::BYPOINTS),
            array(0, self::DRAW),
            array(0.0, self::DRAW)
        );
    }

    /**
     * testing for normalizing points
     *
     * @dataProvider pointsProvider
     */
    public function testNormalizingPoints($points, $isHalfPointer, $expected)
    {
        $em = $this->getEntityManagerMock();
        $obj = new ResultValidator($em);
        $result = $this->getResultMock($points, self::BYPOINTS);
        $obj->validate($result, $isHalfPointer);

        $this->assertSame(
            $expected,
            $result->getPoints(),
            sprintf("Expected result not found. Found '%s'", $result->getPoints())
        );
    }

    /**
     * providing several points
     *
     * @return array
     */
    public function pointsProvider()
    {
        return array(
            array(12.6, true, 12.5),
            array(12.4, true, 12.5),
            array(10.7, false, 10.0),
            array(10.5, false, 10.0),
        );
    }

    private function  getResultMock($points, $id)
    {
        $res = new Result();
        $res->setPoints($points);
        $res->setResultType($this->getResultTypeMock($id));

        return $res;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEntityManagerMock()
    {
        $mock = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getReference')
            ->with('League\Entity\ResultType', $this->greaterThanOrEqual(1))
            ->will($this->returnCallback(array($this, 'callbackResultType')));

        return $mock;
    }

    /**
     * @return ResultType
     */
    public function callbackResultType()
    {
        $args = func_get_args();
        $id = array_pop($args);

        $type = new ResultType();
        $type->setId($id);

        return $type;
    }

    /**
     * @param int $id
     *
     * @return ResultType
     */
    private function getResultTypeMock($id)
    {
        $type = new ResultType();
        $type->setId($id);

        return $type;
    }


}