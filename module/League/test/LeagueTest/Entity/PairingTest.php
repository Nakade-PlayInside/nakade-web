<?php

namespace LeagueTest\Entity;

use League\Entity\Pairing;
use League\Entity\Result;
use User\Entity\User;
use League\Entity\League;
use PHPUnit_Framework_TestCase;
use Zend\InputFilter\InputFilter;

class PairingTest extends PHPUnit_Framework_TestCase
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
            'date' => new \DateTime('2000-01-01'),
            'league' => new League(),
            'black' => new User(),
            'white' => new User(),
            'winner' => new User(),
            'result' => new Result(),
        );
    }
    
    public function testPairingInitialState()
    {
        $object = new Pairing();

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
        $object = new Pairing();
        
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
        $object = new Pairing();
        
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
             
             
    public function testMagicMethods()
    {
        $object = new Pairing();
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
        $object = new Pairing();
        
        $data = array(
            '_id' => 1,
            'result' => 123,
            'winner'  => 232,
            'points'  => 232,
        );
        
        $object->populate($data);
        
        $this->assertSame(
                $data['_id'], 
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
    
    public function testInputFilterMethods()
    {
        $object = new Pairing();
        
        $this->assertInstanceOf(
           'Zend\InputFilter\InputFilter',     
           $object->getInputFilter(),
           '"getInputFilter()" do not work correctly'     
        );
    }
    
    /**
     * @expectedException Exception
     */
    public function testException()
    {
        $object = new Pairing();
        $inputFilter = new InputFilter();
        $object->setInputFilter($inputFilter);
         
    }
}