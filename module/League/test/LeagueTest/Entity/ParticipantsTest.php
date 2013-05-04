<?php

namespace LeagueTest\Entity;

use League\Entity\Participants;
use PHPUnit_Framework_TestCase;

class ParticipantsTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();
    
    public function __construct()
    {
         $this->data = array(
            'id' => 123,
            'lid'  => 232,
            'sid' => 334,
            'uid' => 5,
        );
    }
    
    public function testParticipantsInitialState()
    {
        $object = new Participants();

         foreach($this->data as $key => $value) {
            
            $method = 'get'.ucfirst($key);
            $this->assertNull(
                $object->$method(), 
                sprintf('"%s" should initially be null', $key)
            );
        } 
        
       
    }

    public function testSetsPropertiesCorrectly()
    {
        $object = new Participants();
        
        foreach($this->data as $key => $value) {
            
            //setValue
            $method = 'set'.ucfirst($key);
            $object->$method($value);
            
            //getValue
            $method = 'get'.ucfirst($key);
            $this->assertSame(
                $value, 
                $object->$method(), 
                sprintf('"%s" was not set correctly', $key)
            );
        } 
       
    }

    
    public function testArrayCopy() 
    {
        $object = new Participants();
        
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
             
             
    public function testMagicMethods()
    {
        $object = new Participants();
        $object->_id=2;
        
        $this->assertSame(
           2,
           $object->getId(),     
           '"__set" do not work correctly'     
        );
        
        $this->assertSame(
           $object->_id,
           $object->getId(),     
           '"__get" do not work correctly'     
        );
    }
}