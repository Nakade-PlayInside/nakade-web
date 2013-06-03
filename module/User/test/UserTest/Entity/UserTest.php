<?php
namespace UserTest\Entity;

use User\Entity\User;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    protected $data=array();
    
    public function __construct() {
 
        $this->data=array(
            'id'        => 123,
            'firstname' => 'Hans',
            'lastname'  => 'Kruck',
            'nickname'  => 'Gnomus',
            'title'     => 'Prof. Dr.',
            'sex'       => 'f',
            'birthday'  => new \DateTime('now'),
            'anonym'    => true,
        );
        
    }
            
            
    public function testInitialState()
    {
        $object = new User();
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
        $object = new User();
      
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
        $object = new User();
    
        //testing array copy
        $this->assertInternalType(
            'array',    
            $object->getArrayCopy(), 
            '"getArrayCopy()" do not work correctly'
        );
        
    }
  
}