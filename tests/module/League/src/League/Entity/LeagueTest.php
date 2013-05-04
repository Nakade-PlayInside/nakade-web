<?php

namespace LeagueTest\Entity;

use League\Entity\League;
use PHPUnit_Framework_TestCase;

class LeagueTest extends PHPUnit_Framework_TestCase
{
    protected $data = array();
    public function __construct() {
        
         $this->data = array(
            'id' => 123,
            'title'  => 'some title',
            'sid' => 334,
            'order' => 5,
            'division' => 'Seria B',
            'ruleId' => 231,
            'season' => 1,
            
        );
        
    }
    
    public function testLeagueInitialState()
    {
        $object = new League();

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
        $object = new League();
        
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
        $object = new League();
        
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
             
             
    public function testMagicMethods()
    {
        $object = new League();
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