<?php
namespace League\View\Helper;

use League\View\Helper\Player;
use User\Entity\User as User;
use PHPUnit_Framework_TestCase;

 
class PlayerTest extends PHPUnit_Framework_TestCase
{
    
    public function testFunctionaltity()
    {
        
        $object = new Player();
        
        $this->assertTrue(
        is_callable($object),
        '"Player" is not working as expected'        
        );
       
       
        $user = new User();
        $user->setNickname('Hanne')
               ->setAnonym(true)
               ->setFirstName('Hans');
        
        
        $this->assertSame(
        $object($user),
        'Hanne',
        '"Player" was not set correctly'        
        );
        
        $user->setAnonym(false);
        $this->assertSame(
        $object($user),
        'Hans',
        '"Player" was not set correctly'        
        );
         
    }
    
   
    
   
}
