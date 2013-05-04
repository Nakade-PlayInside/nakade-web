<?php
namespace LeagueTest\Entity;

use League\Entity\Season;
use PHPUnit_Framework_TestCase;

class SeasonTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();
    
    public function __construct() {
        
        $this->data=array(
            'id' => 123,
            'title'  => 'sieg',
            'abbreviation'  => 'sed',
            'active' => TRUE,
            'year'  => new \DateTime('now'),
            'number'  => 3,
        );
        
    }
            
            
    public function testSeasonInitialState()
    {
        $object = new Season();

        foreach($this->data as $key => $value) {
            
            
            if(is_bool($value)) {
                $method = 'is'.ucfirst($key);
            }
            else {
                $method = 'get'.ucfirst($key);
            }
            
            $this->assertNull(
                $object->$method(), 
                sprintf('"%s" should initially be null', $key)
            );
        } 
        
    }

    public function testSetsPropertiesCorrectly()
    {
        $object = new Season();
        
        foreach($this->data as $key => $value) {
            
            $method = 'set'.ucfirst($key);
            $object->$method($value);
            
            if(is_bool($value)) {
                $method = 'is'.ucfirst($key);
            }
            else {
                $method = 'get'.ucfirst($key);
            }
            
            $this->assertSame(
                $object->$method(),
                $this->data[$key],    
                sprintf('"%s" was not set correctly', $key)
            );
        } 
         
    }
    
     public function testArrayCopy() 
    {
        $object = new Season();
        
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
             
             
    public function testMagicMethods()
    {
        $object = new Season();
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