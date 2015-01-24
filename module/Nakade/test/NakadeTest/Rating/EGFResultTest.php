<?php
namespace NakadeTest\Rating;

use League\Entity\Result;
use League\Entity\ResultType;
use Nakade\Rating\EGFResult;
use Nakade\Result\ResultInterface;
use User\Entity\User;
use PHPUnit_Framework_TestCase;

class EGFResultTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();

    /**
     * setUp
     */
    public function setUp() {


        $this->data=array(
            'user' => new User(),
            'rating' => 2000,
            'newRating' => 2030,
            'achievedResult' => 1.0
        );

    }

    /**
     * testing no result
     */
    public function testHasNoResult()
    {
        $user = new User();
        $egfResult = new EGFResult(null);
        $res = $egfResult->getAchievedResult($user);

        $this->assertNull(
            $res,
            sprintf('Null is expected')
        );
    }

    /**
     * testing match was suspended
     */
    public function testMatchSuspended()
    {
        $user = new User();
        $result = new Result();
        $type = new ResultType();
        $type->setId(ResultInterface::SUSPENDED);
        $result->setResultType($type);

        $egfResult = new EGFResult($result);
        $res = $egfResult->getAchievedResult($user);

        $this->assertNull(
            $res,
            sprintf('Null is expected')
        );
    }

    /**
     * testing match draw
     */
    public function testMatchDraw()
    {
        $user = new User();
        $result = new Result();
        $type = new ResultType();
        $type->setId(ResultInterface::DRAW);
        $result->setResultType($type);


        $egfResult = new EGFResult($result);

        $res = $egfResult->getAchievedResult($user);

        $this->assertSame(
            0.5,
            $res,
            sprintf("Expected result '0.5'. Found '%s", $res)
        );
    }

    /**
     * test win
     */
    public function testWinResult()
    {
        $user = new User();
        $user->setId(1);

        $result = new Result();

        $result->setWinner($user);
        $type = new ResultType();
        $type->setId(ResultInterface::RESIGNATION);
        $result->setResultType($type);


        $egfResult = new EGFResult($result);

        $res = $egfResult->getAchievedResult($user);

        $this->assertSame(
            1.0,
            $res,
            sprintf("Expected result '1.0'. Found '%s", $res)
        );
    }

    /**
     * test loss
     */
    public function testLossResult()
    {
        $user = new User();
        $user->setId(1);

        $user2 = new User();
        $user->setId(2);

        $result = new Result();

        $result->setWinner($user);
        $type = new ResultType();
        $type->setId(ResultInterface::RESIGNATION);
        $result->setResultType($type);


        $egfResult = new EGFResult($result);

        $res = $egfResult->getAchievedResult($user2);

        $this->assertSame(
            floatval(0),
            $res,
            sprintf("Expected result '0'. Found '%s", $res)
        );
    }

}