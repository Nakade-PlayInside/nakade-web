<?php
namespace StatsTest;

use Nakade\Result\ResultInterface;
use Stats\Calculation\PlayerMatchStats\PlayerMatchStatsFactory;
use PHPUnit_Framework_TestCase;

class PlayerMatchStatsFactoryTest extends PHPUnit_Framework_TestCase implements ResultInterface
{
    protected $data=array();

    /**
     * setUp
     */
    public function setUp() {

    }

    /**
     * testing userId
     */
    public function testConstructor()
    {
        $obj = new PlayerMatchStatsFactory(5);
        $this->assertSame(
            $obj->getUserId(),
            5,
            sprintf("Expected value not found.  Found '%s'", $obj->getUserId())
        );
    }

    /**
     * testing get match
     */
    public function testInit()
    {
        $obj = new PlayerMatchStatsFactory(5);
        $this->assertNull(
            $obj->getMatch(),
            sprintf("Expected value not found.")
        );
    }

    /**
     * @dataProvider provideWins
     */
    public function testIsWin($uid, $resultTypeId)
    {
        $obj = new PlayerMatchStatsFactory($uid);

        $match = $this->getMatchMock($uid, $resultTypeId);
        $obj->addMatch($match);

        $this->assertTrue(
            $obj->isWin(),
            sprintf("Expected value not found.")
        );
    }

    public function provideWins()
    {
        return array(
            array(7, self::RESIGNATION),
            array(4, self::BYPOINTS),
            array(2, self::FORFEIT),
            array(3, self::ONTIME),
        );
    }

    public function testWin()
    {
        $obj = new PlayerMatchStatsFactory(5);

        $match = $this->getMatchMock(5, self::RESIGNATION);
        $obj->addMatch($match);
        $data = $obj->getData();

        $this->assertTrue(
            count($data['wins']) == 1,
            sprintf("Expected value not found. Found '%s'", count($data['wins']))
        );
    }

    public function testDefeat()
    {
        $obj = new PlayerMatchStatsFactory(4);

        $match = $this->getMatchMock(6, self::RESIGNATION);
        $obj->addMatch($match);
        $data = $obj->getData();

        $this->assertSame(
            1,
            count($data['loss']),
            sprintf("Expected value not found. Found '%s'", count($data['loss']))
        );
    }


    private function getMatchMock($uid, $resultTypeId)
    {
        $mock = $this
            ->getMockBuilder('Season\Entity\Match')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($this->getResultMock($uid, $resultTypeId)));

        return $mock;

    }

    private function getResultMock($uid, $resultTypeId)
    {
        $mock = $this
            ->getMockBuilder('League\Entity\Result')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('hasWinner')
            ->will($this->returnValue(true));

        $mock
            ->expects($this->any())
            ->method('getWinner')
            ->will($this->returnValue($this->getUserMock($uid)));

        $mock
            ->expects($this->any())
            ->method('getResultType')
            ->will($this->returnValue($this->getResultTypeMock($resultTypeId)));

        return $mock;

    }

    private function getUserMock($uid)
    {
        $mock = $this
            ->getMockBuilder('User\Entity\User')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($uid));

        return $mock;
    }

    private function getResultTypeMock($id)
    {
        $mock = $this
            ->getMockBuilder('League\Entity\ResultType')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));

        return $mock;
    }





}