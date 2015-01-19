<?php
namespace NakadeTest\Rating;

use Nakade\Rating\EGFRating;
use Nakade\Rating\Rating;
use PHPUnit_Framework_TestCase;
use Zend\Server\Reflection\ReflectionClass;

class EGFRatingTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();

    public function __construct() {



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

    public function testConstructorAndGetter()
    {
        $playerA = new Rating();
        $playerB = new Rating();

        $egfRating = new EGFRating($playerA, $playerB);

        $this->assertSame(
            $playerA,
            $egfRating->getPlayerA(),
            sprintf("Expected setting not found")
        );

        $this->assertSame(
            $playerB,
            $egfRating->getPlayerB(),
            sprintf("Expected setting not found")
        );
    }

    private function getPlayerRatings($ratingA=null, $ratingB=null)
    {
        $playerA = new Rating();
        $playerB = new Rating();

        $playerA->setRating($ratingA);
        $playerB->setRating($ratingB);

        return new EGFRating($playerA, $playerB);
    }


}