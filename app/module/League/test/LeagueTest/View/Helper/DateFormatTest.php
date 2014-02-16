<?php
namespace League\View\Helper;

use League\View\Helper\DateFormat;
use PHPUnit_Framework_TestCase;


class DateFormatTest extends PHPUnit_Framework_TestCase
{
    
    public function testFunctionaltity()
    {
        $object = new DateFormat();
        $now = "03-04-2013 12:14:33";
        
        $this->assertTrue(
        is_callable($object),
        '"DateFormat" is not working as expected'        
        );
        
        $this->assertSame(
        $object($now),
        '03.04.2013',
        '"DateFormat" was not set correctly'        
        );
         
    }
    
   
}
