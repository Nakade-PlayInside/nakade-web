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

    public function __construct() {

        $user = new User();

        $this->data=array(
            'user' => new User(),
            'rating' => 2000,
            'newRating' => 2030,
            'achievedResult' => 1.0
        );

    }

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