<?php
namespace User\Form\Validator;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

/**
 * Validating password complexity against a treshold.
 * A password should contain uper- and lowercase letters, digits, special
 * chars, spaces and umlauts. Additional penalties are given for repeating
 * letters or words.
 * 
 * The following additional option keys are supported:
 * 'length'    => optimal length of a password
 * 'treshold'  => optional treshold for validation (110 by default)
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class PasswordComplexity extends AbstractValidator {
    
    const INVALID   = 'invalidType';
    const LENGTH    = 'optimal password length';
    const NO_DIGITS = 'no digits found';
    const NO_UPPER  = 'no uppercase letters found';
    const NO_LOWER  = 'no lowercase letters found';
    const NO_SPECIAL = 'no special chars found';
    const NO_UMLAUT  = 'no umlauts found';
    const NO_REPEAT_CHARS  = 'do not repeat letters';
    const NO_REPEAT_WORDS  = 'do not repeat words';
    
    const MAJOR_PENALTY=20;
    const MINOR_PENALTY=5;
    const OPTIMAL_STRENGTH=130;
    
    protected $length;
    protected $treshold;
    
    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID   => "Invalid type given. String expected",
        self::LENGTH    => "Optimal password length: '%length%'.",
        self::NO_DIGITS => "No digits found.",
        self::NO_UPPER  => "No uppercase letters found.",
        self::NO_LOWER  => "No lowercase letters found.",
        self::NO_SPECIAL=> "No special chars found.",
        self::NO_UMLAUT => "No umlauts found.",
        self::NO_REPEAT_CHARS=>  "Do not repeat letters.",
        self::NO_REPEAT_WORDS => "Do not repeat words.",
        
    );

    /**
     * @var array
     */
    protected $messageVariables = array(
        'length' => 'length',
    );

   
    /**
     * Sets validator options
     *
     * @param  string|Traversable $pattern
     * @throws Exception\InvalidArgumentException On missing 'pattern' parameter
     */
    public function __construct($options = null)
    {
       
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }
       
        if (!is_array($options)) {
            
            $options = func_get_args();
            $temp['length'] = array_shift($options);

            if (!empty($options)) {
                $temp['treshold'] = array_shift($options);
            }

            $options = $temp;
        }

        if (!array_key_exists('length', $options)) {
            throw new Exception\InvalidArgumentException(
                "Missing option 'length'"
            );
        }

        if (!array_key_exists('treshold', $options)) {
            $options['treshold'] = 110;
        }
        
        
        $this->setLength($options['length'])
             ->setTreshold($options['treshold']);

        parent::__construct($options);
    }

    /**
     * Returns the optimal length option
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Sets the length option
     *
     * @param  int $length
     * @return PasswordComplexity Provides a fluent interface
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }
    
    /**
     * Returns the optimal treshold option
     *
     * @return int
     */
    public function getTreshold()
    {
        return $this->treshold;
    }

    /**
     * Sets the treshold option
     *
     * @param  int $treshold
     * @return PasswordComplexity Provides a fluent interface
     */
    public function setTreshold($treshold)
    {
        $this->treshold = $treshold;
        return $this;
    }
   

    /**
     * Returns true if and only if $value matches against the treshold option
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($value);

        $strength = self::OPTIMAL_STRENGTH - $this->checkViolations($value);
        if ($this->getTreshold() >= $strength) {
             return false;
        }
        

        return true;
    }
    
    /**
     * check for violations against proper 
     * password composition. For each violations
     * a penalty is added. Bonus are given for exceeded
     * length and using more than one word.
     * 
     * @param string $value
     * @return int
     */
    
    public function checkViolations($value)
    {
        $violation=0;
        
        $violation+=$this->testOptimalLength($value);
        $violation+=$this->testSentences($value);
        
        if(false === $this->testLowcase($value)){
            $this->error(self::NO_LOWER);
            $violation+=self::MAJOR_PENALTY;
        }  
         
        if(false === $this->testUppercase($value)){
            $this->error(self::NO_UPPER);
            $violation+=self::MAJOR_PENALTY;
        }
        
        if(false === $this->testSpecialChars($value)){
            $this->error(self::NO_SPECIAL);
            $violation+=self::MAJOR_PENALTY;
        } 
        
        if(false === $this->testDigits($value)){
            $this->error(self::NO_DIGITS);
            $violation+=self::MAJOR_PENALTY;
        } 
        
        if(false === $this->testWhitespaces($value)){
            $violation+=self::MINOR_PENALTY;
        }  
        
        if(false === $this->testUmlaute($value)){
            $this->error(self::NO_UMLAUT);
            $violation+=self::MINOR_PENALTY;
        }
        
        if(true === $this->testRepeatingChars($value)){
            $this->error(self::NO_REPEAT_CHARS);
            $violation+=self::MAJOR_PENALTY;
        }  
        
        if(true === $this->testRepeatingWords($value)){
            $this->error(self::NO_REPEAT_WORDS);
            $violation+=self::MAJOR_PENALTY;
        }  
       
        return $violation;
    }
    
    /**
     * Test for optimal length. 
     * if exceeded, a bonus is provided. otherwise
     * a penalty is given for each letter missing.
     * 
     * @param string $value
     * @return int
     */
    public function testOptimalLength($value)
    {
        $res = $this->getLength() - strlen($value);
        if($res > 0) {
            $this->error(self::LENGTH);
        }
        return $res * self::MINOR_PENALTY;
    }
    
    /**
     * Test for words found. 
     * if exceeded, a bonus is provided.
     * 
     * @param string $value
     * @return int
     */
    public function testSentences($value)
    {
        $res = 1-str_word_count($value);
        return $res * self::MINOR_PENALTY;
    }
    
    /**
     * Test value for lowcase letters. 
     * return false if not found
     * 
     * @param string $value
     * @return boolean
     */
    public function testLowcase($value)
    {
        $pattern = '/[a-z]/';
        return (bool) preg_match($pattern, $value);
        
    }
    
    /**
     * Test value for uppercase letters. 
     * return false if not found
     * 
     * @param string $value
     * @return boolean
     */
    public function testUppercase($value)
    {
        $pattern = '/[A-Z]/';
        return (bool) preg_match($pattern, $value);
    }
    
    /**
     * Test value for special chars. 
     * return false if not found
     * 
     * @param string $value
     * @return boolean
     */
    public function testSpecialChars($value)
    {
        $pattern = sprintf('/[%s]/', 
                 preg_quote('+-*=%&#_.,;:\§@€µ?$!{}[]()|'));
              
        return (bool) preg_match($pattern, $value);
    }
    
    /**
     * Test value for digits. 
     * return false if not found
     * 
     * @param string $value
     * @return boolean
     */
    public function testDigits($value)
    {
        $pattern = '/[0-9]/';
        return (bool) preg_match($pattern, $value);
    }
    
    /**
     * Test value for spaces. 
     * return false if not found
     * 
     * @param string $value
     * @return boolean
     */
    public function testWhitespaces($value)
    {
        $pattern = '/[[:space:]]/';
        return (bool) preg_match($pattern, $value);
    }
    
    /**
     * Test value for Umlaute. 
     * return false if not found
     * 
     * @param string $value
     * @return boolean
     */
    public function testUmlaute($value)
    {
        $pattern = '/[äöüßÄÖÜÀÁáÂâÈèÉéÊêÙùÚúÇç]/';
        return (bool) preg_match($pattern, $value);
    }
    
     /**
     * Test value for repeating chars.
     * return true if found more than three in a row . 
     * 
     * @param string $value
     * @return boolean
     */
    public function testRepeatingChars($value)
    {
        $pattern = '/(.)\1\1/';
        return (bool) preg_match($pattern, $value);
    }
    
    /**
     * Test value for repeating words.
     * return true if found more than three in a row . 
     * 
     * @param string $value
     * @return boolean
     */
    public function testRepeatingWords($value)
    {
        $pattern = '/([a-zA-Z]{3,})(.)*\1\z/';
        return (bool) preg_match($pattern, $value);
    }
    
}

?>
