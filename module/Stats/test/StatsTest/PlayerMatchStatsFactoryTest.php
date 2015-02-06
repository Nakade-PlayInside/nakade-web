<?php
namespace StatsTest;

use Nakade\Result\ResultInterface;
use Stats\Calculation\PlayerMatchStats\PlayerMatchStatsFactory;
use PHPUnit_Framework_TestCase;

class PlayerMatchStatsFactoryTest extends PHPUnit_Framework_TestCase implements ResultInterface
{
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
        $data = $obj->getData();

        $this->assertTrue(
            count($data['wins']) == 1,
            sprintf("Expected value not found.")
        );
    }

    /**
     * @return array
     */
    public function provideWins()
    {
        return array(
            array(7, self::RESIGNATION),
            array(4, self::BYPOINTS),
            array(2, self::FORFEIT),
            array(3, self::ONTIME),
        );
    }

    /**
     * test number of wins
     */
    public function testNumberOfWins()
    {
        $winnerId = 5;
        $wins = array(
            array($winnerId, self::BYPOINTS),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::ONTIME),
            array($winnerId, self::FORFEIT),
            array($winnerId, self::RESIGNATION),
        );

        $obj = new PlayerMatchStatsFactory($winnerId);
        $matches = $this->getMatches($wins);

        foreach ($matches as $match) {
            $obj->addMatch($match);
        }

        $data = $obj->getData();
        $this->assertSame(
            count($wins),
            count($data['wins']),
            sprintf("Expected value not found.")
        );
    }

    /**
     * Making an array of matches from dataProviders.
     * First value is uid, second resultTypeId
     *
     * @param array $data
     *
     * @return array
     */
    private function getMatches(array $data)
    {
        $matches = array();
        foreach ($data as $matchData) {
            $matches[] = $this->getMatchMock($matchData[0], $matchData[1]);
        }

        return $matches;
    }

    /**
     * @dataProvider provideDraws
     */
    public function testIsDraw($uid, $resultTypeId)
    {

        $obj = new PlayerMatchStatsFactory($uid+2);

        $match = $this->getMatchMock($uid, $resultTypeId);
        $obj->addMatch($match);
        $data = $obj->getData();

        $this->assertTrue(
            count($data['draw']) == 1,
            sprintf("Expected value not found.")
        );
    }

    /**
     * @return array
     */
    public function provideDraws()
    {
        return array(
            array(7, self::DRAW),
            array(4, self::DRAW),
            array(2, self::DRAW),
            array(3, self::DRAW),
        );
    }

    /**
     * test number of draws
     */
    public function testNumberOfDraws()
    {
        $userId = 5;
        $draws = array(
            array(1, self::DRAW),
            array(2, self::DRAW),
            array(3, self::DRAW),
            array(4, self::DRAW),
            array(6, self::DRAW),
        );

        $obj = new PlayerMatchStatsFactory($userId);
        $matches = $this->getMatches($draws);

        foreach ($matches as $match) {
            $obj->addMatch($match);
        }

        $data = $obj->getData();
        $this->assertSame(
            count($draws),
            count($data['draw']),
            sprintf("Expected value not found.")
        );
    }

    /**
     * @dataProvider provideWins
     */
    public function testIsDefeats($uid, $resultTypeId)
    {

        $obj = new PlayerMatchStatsFactory($uid+2);

        $match = $this->getMatchMock($uid, $resultTypeId);
        $obj->addMatch($match);
        $data = $obj->getData();

        $this->assertTrue(
            count($data['loss']) == 1,
            sprintf("Expected value not found.")
        );
    }

    /**
     * test number of wins
     */
    public function testNumberOfDefeats()
    {
        $winnerId = 5;
        $wins = array(
            array($winnerId, self::BYPOINTS),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::ONTIME),
            array($winnerId, self::FORFEIT),
            array($winnerId, self::RESIGNATION),
        );

        $obj = new PlayerMatchStatsFactory($winnerId+1);
        $matches = $this->getMatches($wins);

        foreach ($matches as $match) {
            $obj->addMatch($match);
        }

        $data = $obj->getData();
        $this->assertSame(
            count($wins),
            count($data['loss']),
            sprintf("Expected value not found.")
        );
    }

    /**
     * test number of games played
     */
    public function testSuspendedAndPlayed()
    {
        $winnerId = 5;
        $wins = array(
            array($winnerId, self::BYPOINTS),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::SUSPENDED),
            array($winnerId, self::FORFEIT),
            array($winnerId, self::RESIGNATION),
        );

        $obj = new PlayerMatchStatsFactory($winnerId+1);
        $matches = $this->getMatches($wins);

        foreach ($matches as $match) {
            $obj->addMatch($match);
        }
        $data = $obj->getData();

        $this->assertSame(
            count($wins)-1,
            count($data['played']),
            sprintf("Expected value not found.")
        );
    }

    /**
     * test number of no of consecutive wins
     */
    public function testNoOfConsecutiveWins()
    {
        $winnerId = 5;
        $wins = array(
            array($winnerId, self::BYPOINTS),
            array($winnerId+1, self::BYPOINTS),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::FORFEIT),
            array($winnerId, self::RESIGNATION),
            array($winnerId+1, self::RESIGNATION),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::FORFEIT),
            array($winnerId, self::RESIGNATION),
        );

        $obj = new PlayerMatchStatsFactory($winnerId);
        $matches = $this->getMatches($wins);

        foreach ($matches as $match) {
            $obj->addMatch($match);
        }
        $data = $obj->getData();

        $this->assertSame(
            4,
            count($data['consecutiveWins']),
            sprintf("Expected value not found.")
        );
    }


    /**
     * test number of no of consecutive wins
     */
    public function testAll()
    {
        $winnerId = 5;
        $wins = array(
            array($winnerId, self::BYPOINTS),
            array($winnerId+1, self::BYPOINTS),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::SUSPENDED),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::FORFEIT),
            array($winnerId, self::RESIGNATION),
            array($winnerId+1, self::RESIGNATION),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::FORFEIT),
            array($winnerId, self::RESIGNATION),
            array($winnerId, self::SUSPENDED),
            array($winnerId+1, self::DRAW),
        );

        $obj = new PlayerMatchStatsFactory($winnerId);
        $matches = $this->getMatches($wins);

        foreach ($matches as $match) {
            $obj->addMatch($match);
        }
        $data = $obj->getData();

        $this->assertSame(
            8,
            count($data['wins']),
            sprintf("Expected number of wins not found.")
        );

        $this->assertSame(
            2,
            count($data['loss']),
            sprintf("Expected number of defeats not found.")
        );

        $this->assertSame(
            4,
            count($data['consecutiveWins']),
            sprintf("Expected number of consecutive wins not found.")
        );

        $this->assertSame(
            1,
            count($data['draw']),
            sprintf("Expected number of draws not found.")
        );

        $this->assertSame(
            11,
            count($data['played']),
            sprintf("Expected number of played matches not found.")
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