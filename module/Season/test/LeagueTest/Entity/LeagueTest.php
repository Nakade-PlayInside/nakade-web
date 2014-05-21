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
            'number' => 5,
            'division' => 'Seria B',
            'ruleId' => 231,
        );
        
    }

    public function testLeagueInitialState()
    {
        $object = new League();

        $keys = array_keys($this->data);
        foreach($keys as $key) {
            
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
    
}