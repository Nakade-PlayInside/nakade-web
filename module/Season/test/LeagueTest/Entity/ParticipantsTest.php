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
            'gamesPlayed' => 3,
            'gamesSuspended' => 4,
            'gamesWin'       => 5,
            'gamesDraw'      => 6,
            'gamesLost'      => 7,
            'gamesPoints'    => 14,
            'firstTiebreak'  => 12.5,
            'secondTiebreak' => 123,
            'thirdTiebreak' => 12
        );
    }
    
    public function testParticipantsInitialState()
    {
        $object = new Participants();

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
    
    public function testPopulate()
    {
        $object = new Participants();
        $object->populate($this->data);
       
        foreach($this->data as $key => $value) {
            
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
    
}