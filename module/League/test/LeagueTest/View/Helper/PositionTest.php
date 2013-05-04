<?php
namespace League\View\Helper;

use League\View\Helper\Position;
use League\Entity\Position as Pos;
use PHPUnit_Framework_TestCase;
 
class PositionTest extends PHPUnit_Framework_TestCase
{
    public function testFunctionaltity()
    {
        
        $object = new Position();
        
        $this->assertTrue(
        is_callable($object),
        '"Position" is not working as expected'        
        );
       
        $user = new Pos();
        
        $this->assertSame(
            $object($user),
            1,
            '"Position" was not set correctly'        
        );
        
        $user->setGamesPlayed(1);
        
        $this->assertSame(
            $object($user),
            1,
            '"Position" was not set correctly'        
        );
        
        $this->assertSame(
            $object($user),
            2,
            '"Position" was not set correctly'        
        );
       
         
    }
    
    
   
    
   
}
