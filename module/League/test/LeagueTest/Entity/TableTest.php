<?php

namespace LeagueTest\Entity;

use League\Entity\Table;
use PHPUnit_Framework_TestCase;

class TableTest extends PHPUnit_Framework_TestCase
{
   
    protected $data = array(
        'id' => 123,
        'lid'  => 232,
        'uid' => 334,
        'gamesPlayed' => 5,
        'win' => 99,
        'loss' => 231,
        'jigo' => 44,
        'gamesSuspended' => 3,
        'tiebreaker1' => 10.5,
        'tiebreaker2' => 4.5,
        'player' => 1,
        'league' => 1
    );
    
    public function getNewObject()
    {
        return new Table();
    }        
    
    public function testPositionInitialState()
    {
        $object = $this->getNewObject();

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
        $object = $this->getNewObject();
        
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
        $object = $this->getNewObject();
        
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
             
             
    public function testMagicMethods()
    {
       $object = $this->getNewObject();
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
    
    public function testPopulateMethod()
    {
        $object = $this->getNewObject();
        
        $data = array(
            'gamesPlayed' => 123,
            'gamesSuspended'  => 232,
            'jigo' => 334,
            'win' => 5,
            'loss' => 99,
            'tiebreaker1' => 231,
            'tiebreaker2' => 44,
        );
        
        $object->populate($data);
        
        foreach($data as $key => $value) {
            
            $property = '_'.$key;
            $this->assertSame(
                $value, 
                $object->$property, 
                sprintf('"%s" was not set correctly', $key)
            );
        } 
     
      
    }
}