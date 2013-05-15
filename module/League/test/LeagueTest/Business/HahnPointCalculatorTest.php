<?php

namespace LeagueTest\Business;

use League\Business\HahnPointCalculator;
use PHPUnit_Framework_TestCase;

class HahnPointCalculatorTest extends PHPUnit_Framework_TestCase
{
    
    public function testReturnValues()
    {
       $max=40;
       $offset=20;
       $skip=0;
       $points=10.5;
        
       $object = new HahnPointCalculator($offset, $max);

       $this->assertSame(
                floatval($max), 
                $object->getPointsForResign(), 
                sprintf('"%s" was not set correctly', 'max')
               
       );
        
       $this->assertSame(
                floatval($skip), 
                $object->getPointsForSkip(), 
                sprintf('"%s" was not set correctly', 'skip')
               
       );
       
       $this->assertSame(
                floatval($offset) + floatval($points), 
                $object->getPointsForWin($points), 
                sprintf('"%s" was not set correctly', 'winning points')
               
       );
       
        
       $this->assertSame(
                floatval($offset) - floatval($points), 
                $object->getPointsForLoss($points), 
                sprintf('"%s" was not set correctly', 'loosing points')
               
       );
       
    }

   
    public function testOptionalValues()
    {
       $max=40;
       $offset=20;
       $skip=10.5;
       $points=10.5;
        
       $object = new HahnPointCalculator($offset, $max, $skip);

       $this->assertSame(
                floatval($max), 
                $object->getPointsForResign(), 
                sprintf('"%s" was not set correctly', 'max')
               
       );
        
       $this->assertSame(
                floatval($skip), 
                $object->getPointsForSkip(), 
                sprintf('"%s" was not set correctly', 'skip')
               
       );
       
       $this->assertSame(
                floatval($offset) + floatval($points), 
                $object->getPointsForWin($points), 
                sprintf('"%s" was not set correctly', 'winning points')
               
       );
       
        
       $this->assertSame(
                floatval($offset) - floatval($points), 
                $object->getPointsForLoss($points), 
                sprintf('"%s" was not set correctly', 'loosing points')
               
       );
       
    }
    
}