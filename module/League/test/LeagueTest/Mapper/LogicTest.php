<?php
namespace League\Standings;

use PHPUnit_Framework_TestCase;

/**
 * Class ResultsTest
 *
 * @package League\Standings
 */
class LogicTest extends PHPUnit_Framework_TestCase
{
    /**
     * testGetResultTypes
     */
    public function testNow()
    {
        $now = new \DateTime();
        $myDate = clone $now;
        $myDate->modify('+5 day');

        $this->assertGreaterThan($now, $myDate);

        //before

    }



}