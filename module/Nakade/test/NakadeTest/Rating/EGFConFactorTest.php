<?php
namespace NakadeTest\Rating;

use Nakade\Rating\EGFConFactor;

use PHPUnit_Framework_TestCase;

class EGFConFactorTest extends PHPUnit_Framework_TestCase
{
    private $object;

    public function setUp()
    {
        $this->object = new EGFConFactor();
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
     * @return EGFConFactor
     */
    private function getObject()
    {
        return $this->object;
    }


}