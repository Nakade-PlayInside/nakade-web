<?php
namespace UserTest\Validator;

use User\Form\Validator\CommonPassword;
use PHPUnit_Framework_TestCase;


class CommonPasswordTest extends PHPUnit_Framework_TestCase {
    
    
    protected $data=array();
    
    public function __construct() {
 
        $this->data=array(
            'test',
            'Albert',
            'Yussuf',
            'Ryo',
        );
        
    }
    
    public function testSetsPropertiesCorrectly()
    {
        $value   = array('test');
        $object = new CommonPassword(array('commons'=> $value));
        
        $this->assertSame(
            $object->getCommons(),
            $value,    
            sprintf('"%s" was not set correctly', 'commons')
        );
        
    }

    /**
    * @expectedException Zend\Validator\Exception\InvalidArgumentException
    */
    public function testInvalidOptionsException()
    {
        $options = "hi";
        new CommonPassword($options);
         
    }
    
    /**
    * @expectedException Zend\Validator\Exception\InvalidArgumentException
    */
    public function testMissingOptionException()
    {
        $options = array('nix'=>'test');
        new CommonPassword($options);
         
    }
   
    public function testIsValid()
    {
        $object = new CommonPassword(array('commons'=> $this->data));
        
        $this->assertFalse(
            $object->isValid('test'),
            sprintf('"%s" should return false', 'test')
        );
        $this->assertFalse(
            $object->isValid('Ryo'),
            sprintf('"%s" should return false', 'Ryo')
        );
        $this->assertFalse(
            $object->isValid('aLBeRt'),
            sprintf('"%s" should return false', 'aLBeRt')
        );
        $this->assertTrue(
            $object->isValid('Sunny'),
            sprintf('"%s" should return true', 'Sunny')
        );
        
        
    }

    
   
}

?>
