<?php
namespace NakadeTest\Rating;

use Nakade\Rating\EGFRating;
use Nakade\Rating\Rating;
use PHPUnit_Framework_TestCase;

class EGFRatingTest extends PHPUnit_Framework_TestCase
{
    protected $rating=array();
    private $playerA;
    private $playerB;
    private $object;

    public function setUp()
    {
        $playerA = new Rating();
        $playerB = new Rating();

        $playerA->setRating(2000);
        $playerA->setAchievedResult(1.0);
        $playerB->setRating(1900);
        $playerB->setAchievedResult(0);

        $this->playerA = $playerA;
        $this->playerB = $playerB;
        $this->object = new EGFRating($playerA, $playerB);

    }

    /**
     * @return Rating
     */
    public function getPlayerA()
    {
        return $this->playerA;
    }

    /**
     * @return Rating
     */
    public function getPlayerB()
    {
        return $this->playerB;
    }

    /**
     * @return EGFRating
     */
    public function getObject() {

        return $this->object;
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
     * test if player A and B are initialized
     */
    public function testConstructorAndGetter()
    {
        $this->assertSame(
            $this->getPlayerA(),
            $this->getObject()->getPlayerA(),
            sprintf("Expected setting not found")
        );

        $this->assertSame(
            $this->getPlayerB(),
            $this->getObject()->getPlayerB(),
            sprintf("Expected setting not found")
        );
    }

    /**
     * test if rating difference is calculated
     */
    public function testGetRatingDifference()
    {
        $dif = abs($this->getPlayerA()->getRating() - $this->getPlayerB()->getRating());

        $this->assertSame(
            $dif,
            $this->getObject()->getRatingDifference(),
            sprintf("Expected value not found. Found '%s'.",
                $this->getObject()->getRatingDifference()
            )
        );
    }

    /**
     * test if stronger and weaker player are distinguished
     */
    public function testGetStrongerAndWeakerPlayer()
    {
        $this->assertSame(
            $this->getPlayerA(),
            $this->getObject()->getStrongerPlayer(),
            sprintf("Expected player not found.")
        );

        $this->assertSame(
            $this->getPlayerB(),
            $this->getObject()->getWeakerPlayer(),
            sprintf("Expected player not found.")
        );
    }

    /**
     * test calculation of factor
     */
    public function testGetFactorA()
    {
        $this->assertSame(
            105,
            $this->getObject()->getFactorA(2000),
            sprintf("Expected value '%s' not found.", 105)
        );
    }

    /**
     * @dataProvider conProvider
     */
    public function testGetCon($rating, $expected)
    {
        $con = $this->getObject()->getCon($rating);

        $this->assertSame(
            $expected,
            $con,
            sprintf("Expected value '%s' not found.", $expected)
        );

    }

    /**
     * providing different test cases for con
     *
     * @return array
     */
    public function conProvider()
    {
        return array(
            array(1000, 70),
            array(1050, 67),
            array(2100, 24),
            array(600, 90)
        );
    }

    /**
     * test calculation method
     */
    public function testDoCalculation()
    {
        $playerA = new Rating();
        $playerB = new Rating();
        $egfRating = new EGFRating($playerA, $playerB);

        $this->assertFalse(
            $egfRating->doCalculation(),
            sprintf("Expected value not found.")
        );

        $this->assertTrue(
            $this->getObject()->doCalculation(),
            sprintf("Expected value not found.")
        );
    }

    /**
     * @param int $ratingA
     * @param int $ratingB
     *
     * @return EGFRating
     */
    private function getNewRatingCalculation($ratingA, $ratingB)
    {
        $playerA = new Rating();
        $playerA->setRating($ratingA);
        $playerA->setAchievedResult(1.0);

        $playerB = new Rating();
        $playerB->setRating($ratingB);
        $playerB->setAchievedResult(0);

        $obj = new EGFRating($playerA, $playerB);
        $obj->doCalculation();

        return $obj;
    }

    /**
     * @dataProvider ratingProvider
     */
    public function testNewRating($ratingA, $ratingB, $expectedA, $expectedB)
    {
        $obj = $this->getNewRatingCalculation($ratingA, $ratingB);

        $this->assertSame(
            $expectedA,
            $obj->getPlayerA()->getNewRating(),
            sprintf("Expected value not found.")
        );

        $this->assertSame(
            $expectedB,
            $obj->getPlayerB()->getNewRating(),
            sprintf("Expected value not found.")
        );

    }

    /**
     * providing different test cases for new rating
     *
     * @return array
     */
    public function ratingProvider()
    {
        return array(
            array(2400, 2400, 2407, 2392),
            array(1900, 1850, 1912, 1837),
            array(320, 400, 381, 340)
        );
    }




}