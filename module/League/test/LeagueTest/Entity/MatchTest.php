<?php

namespace LeagueTest\Entity;

use League\Entity\Match;
use League\Entity\Result;
use User\Entity\User;
use League\Entity\League;
use PHPUnit_Framework_TestCase;

class MatchTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();
    
    public function __construct()
    {
         $this->data = array(
            'id' => 123,
            'lid'  => 232,
            'blackId' => 334,
            'whiteId' => 5,
            'resultId' => 99,
            'winnerId' => 231,
            'points' => 44,
            'matchday' => 3,
            'date' => new \DateTime('2000-01-01'),
            'league' => new League(),
            'black' => new User(),
            'white' => new User(),
            'winner' => new User(),
            
        );
    }
    
    public function testPairingInitialState()
    {
        $object = new Match();

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
        $object = new Match();
        
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
        $object = new Match();
        
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
    
    
    public function testPopulateMethod()
    {
        $object = new Match();
        
        $data = array(
            'pid' => 1,
            'result' => 123,
            'winner'  => 232,
            'points'  => 232,
        );
        
        $object->populate($data);
        
        $this->assertSame(
                $data['pid'], 
                $object->getId(), 
                sprintf('"id" was not set correctly')
        );
        
        $this->assertSame(
                $data['result'], 
                $object->getResultId(), 
                sprintf('"resultId" was not set correctly')
        );
        
        $this->assertSame(
                $data['winner'], 
                $object->getWinnerId(), 
                sprintf('"winnerId" was not set correctly')
        );
        
        $this->assertSame(
                $data['points'], 
                $object->getPoints(), 
                sprintf('"points" was not set correctly')
        );
         
    }
    
    
}