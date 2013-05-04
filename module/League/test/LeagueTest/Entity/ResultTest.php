<?php
namespace LeagueTest\Entity;

use League\Entity\Result;
use PHPUnit_Framework_TestCase;

class ResultTest extends PHPUnit_Framework_TestCase
{
    protected $data = array(
            'id' => 123,
            'result'  => 'sieg',
        );
    
    public function testResultInitialState()
    {
        $object = new Result();
        
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
        $object = new Result();
        
       foreach($this->data as $key => $value) {
            
            $method = 'set'.ucfirst($key);
            $object->$method($value);
            
            $method = 'get'.ucfirst($key);
            
            $this->assertSame(
                $object->$method(),
                $this->data[$key],    
                sprintf('"%s" was not set correctly', $key)
            );
        } 
    }
    
    public function testArrayCopy() 
    {
        $object = new Result();
        
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
             
             
    public function testMagicMethods()
    {
        $object = new Result();
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