<?php
namespace UserTest\Validator;

use User\Form\Validator\PasswordComplexity;
use PHPUnit_Framework_TestCase;


class PasswordComplexityTest extends PHPUnit_Framework_TestCase {
    
    
    protected $data=array();
    protected $object;
    protected $length=8;
    
    public function __construct() {
 
        
        $this->object = new PasswordComplexity(
                array(
                    'length'=> $this->length
                )
        );
        
        $this->data=array(
            'test',
            'Albert',
            'Yussuf',
            'Ryo',
        );
        
    }
    
    public function testConstructor()
    {
        $this->assertSame(
            $this->object->getLength(),
            $this->length,    
            sprintf('"%s" was not set correctly', 'length')
        );
        
        $this->assertSame(
            $this->object->getTreshold(),
            110,    
            sprintf('"%s" default was not set correctly', 'treshold')
        );
        
        $value   = array('100');
        $object = new PasswordComplexity(
                array('length' => '10', 'treshold' => $value ));
        
        $this->assertSame(
            $object->getTreshold(),
            $value,    
            sprintf('"%s" was not set correctly', 'treshold')
        );
        
    }

    
    /**
    * @expectedException Zend\Validator\Exception\InvalidArgumentException
    */
    public function testMissingLengthException()
    {
        $options = array('nix'=>'test');
        new PasswordComplexity($options);
         
    }
   
    public function testOptimalLength()
    {
        $value = 'test';
        $this->assertSame(
            $this->object->testOptimalLength($value),
            ($this->length - strlen($value)) * PasswordComplexity::MINOR_PENALTY,    
            sprintf('"%s" was not calculated correctly', 'OptimalLength')
        );
        
        $this->greaterThan(
            $this->object->testOptimalLength($value),
            0,    
            sprintf('"%s" is not a positive value', 'OptimalLength')
        );
        
        $this->lessThan(
            $this->object->testOptimalLength('testtesttest'),
            0,    
            sprintf('"%s" is not a negative value', 'OptimalLength')
        );
    }
    
    public function testTestSentences()
    {
        $this->assertSame(
            $this->object->testSentences('test'),
            0,    
            sprintf('"%s" is not 0', 'sentences')
        );
        
        $this->lessThan(
            $this->object->testSentences('test test test'),
            0,    
            sprintf('"%s" is not a negative value', 'sentences')
        );
    }
    
    public function testTestLowcase()
    {
        $this->assertTrue(
            $this->object->testLowcase('test'),
            sprintf('"%s" is not true', 'lowcase')
        );
        
        $this->assertFalse(
            $this->object->testLowcase('TEST'),
            sprintf('"%s" is not false', 'lowcase')
        );
        
    }
    
    public function testTestUppercase()
    {
        $this->assertTrue(
            $this->object->testUppercase('TEST'),
            sprintf('"%s" is not true', 'upercase')
        );
        
        $this->assertFalse(
            $this->object->testUppercase('test'),
            sprintf('"%s" is not false', 'uppercase')
        );
        
    }
    
     public function testTestDigits()
    {
        $this->assertTrue(
            $this->object->testDigits('123'),
            sprintf('"%s" is not true', 'digits')
        );
        
        $this->assertFalse(
            $this->object->testDigits('test'),
            sprintf('"%s" is not false', 'digits')
        );
        
    }
    
    
    public function testTestUmlauts()
    {
        $this->assertTrue(
            $this->object->testUmlaute('äö'),
            sprintf('"%s" is not true', 'umlauts')
        );
        
        $this->assertFalse(
            $this->object->testUmlaute('test'),
            sprintf('"%s" is not false', 'umlauts')
        );
        
    }
    
    public function testTestSpecials()
    {
        $this->assertTrue(
            $this->object->testSpecialChars('&%'),
            sprintf('"%s" is not true', 'special chars')
        );
        
        $this->assertFalse(
            $this->object->testSpecialChars('test'),
            sprintf('"%s" is not false', 'special chars')
        );
        
    }
    
    public function testTestWhitespace()
    {
        $this->assertTrue(
            $this->object->testWhitespaces('a o'),
            sprintf('"%s" is not true', 'space')
        );
        
        $this->assertFalse(
            $this->object->testWhitespaces('test'),
            sprintf('"%s" is not false', 'space')
        );
        
    }
    
    public function testTestRepeatingChars()
    {
        $this->assertTrue(
            $this->object->testRepeatingChars('aabacccd'),
            sprintf('"%s" is not true', 'repeating chars')
        );
        
        $this->assertFalse(
            $this->object->testRepeatingChars('aabcdd'),
            sprintf('"%s" is not false', 'repeating chars')
        );
        
        $this->assertFalse(
            $this->object->testRepeatingChars('test'),
            sprintf('"%s" is not false', 'repeating chars')
        );
        
    }
    
    public function testTestRepeatingWords()
    {
        $this->assertTrue(
            $this->object->testRepeatingWords('mein mein'),
            sprintf('"%s" is not true', 'repeating words')
        );
        
        $this->assertFalse(
            $this->object->testRepeatingWords('aabcdd'),
            sprintf('"%s" is not false', 'repeating words')
        );
        
        $this->assertFalse(
            $this->object->testRepeatingWords('mein test'),
            sprintf('"%s" is not false', 'repeating words')
        );
        
    }
    
    
    public function testIsValid()
    {
        
        $this->assertTrue(
            $this->object->isValid('Mein Bein@123.ö'),
            sprintf('"%s" is not true', 'isValid')
        );
        
        $this->assertFalse(
            $this->object->isValid('aaa'),
            sprintf('"%s" is not false', 'isValid')
        );
        
        $penalty50 = 'abCDghtz'; 
        $this->object->setTreshold(80);
        
        $this->assertFalse(
            $this->object->isValid($penalty50),
            sprintf('"%s" is not false', 'isValid')
        );
        $this->assertTrue(
            $this->object->isValid($penalty50.'a'),
            sprintf('"%s" is not true', 'isValid')
        );
        
        $penalty70 = 'aaabbb12';
        $this->object->setTreshold(60);
        
        $this->assertFalse(
            $this->object->isValid($penalty70),
            sprintf('"%s" is not false', 'isValid')
        );
        $this->assertTrue(
            $this->object->isValid($penalty70.'a'),
            sprintf('"%s" is not true', 'isValid')
        );
        
        $penalty105 = 'aaa aaa';
        $this->object->setTreshold(25);
        
        $this->assertFalse(
            $this->object->isValid($penalty105),
            sprintf('"%s" is not false', 'isValid')
        );
        $this->assertTrue(
            $this->object->isValid($penalty105.'a'),
            sprintf('"%s" is not true', 'isValid')
        );
    }

    
   
}

?>
