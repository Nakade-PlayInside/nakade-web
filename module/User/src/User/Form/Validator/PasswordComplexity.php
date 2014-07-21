<?php
namespace User\Form\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

/**
 * Validating password complexity against a threshold.
 * A password can contain upper- and lowercase letters, digits, special
 * chars, spaces and umlauts. Additional penalties are given for repeating
 * letters or words.
 *
 * The following option keys are supported:
 * 'length'    => length of a password
 * 'threshold'  => threshold for validation
 *
 * @package User\Form\Validator
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

    const MAJOR_PENALTY = 20;
    const MINOR_PENALTY = 5;
    const OPTIMAL_STRENGTH = 130;

    private $length = 8;
    private $threshold = 80;

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
     * @param null $options
     */
    public function __construct($options = null)
    {
     //@todo: translation

        if (!empty($options)  && is_array($options)) {

            if (array_key_exists('length', $options)) {
                $this->length = $options['length'];
            }

            if (array_key_exists('threshold', $options)) {
                $this->threshold = $options['threshold'];
            }
        }

        parent::__construct($options);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        $value = strval($value);
        $this->setValue($value);

        $strength = self::OPTIMAL_STRENGTH - $this->checkViolations($value);
        if ($this->threshold >= $strength) {
             return false;
        }


        return true;
    }

    /**
     * @param string $value
     *
     * @return int
     */
    private function checkViolations($value)
    {
        $violation=0;

        $lenFactor = $this->length - strlen($value);
        if ($lenFactor > 0) {
            $this->error(self::LENGTH);
        }
        $violation+= $lenFactor * self::MINOR_PENALTY;

        //better for sentences
        $violation+= (1-str_word_count($value)) * self::MINOR_PENALTY;

        if (!$this->hasLowercaseLetters($value)) {
            $this->error(self::NO_LOWER);
            $violation+=self::MAJOR_PENALTY;
        }

        if (!$this->hasUppercaseLetter($value)) {
            $this->error(self::NO_UPPER);
            $violation+=self::MAJOR_PENALTY;
        }

        if (!$this->hasSpecialChars($value)) {
            $this->error(self::NO_SPECIAL);
            $violation+=self::MAJOR_PENALTY;
        }

        if (!$this->hasDigits($value)) {
            $this->error(self::NO_DIGITS);
            $violation+=self::MAJOR_PENALTY;
        }

        if (!$this->hasWhitespaces($value)) {
            $violation+=self::MINOR_PENALTY;
        }

        if (!$this->hasUmlaute($value)) {
            $this->error(self::NO_UMLAUT);
            $violation+=self::MINOR_PENALTY;
        }

        if ($this->hasRepeatingChars($value)) {
            $this->error(self::NO_REPEAT_CHARS);
            $violation+=self::MAJOR_PENALTY;
        }

        if ($this->hasRepeatingWords($value)) {
            $this->error(self::NO_REPEAT_WORDS);
            $violation+=self::MAJOR_PENALTY;
        }

        return $violation;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function hasLowercaseLetters($value)
    {
        $pattern = '/[a-z]/';
        return (bool) preg_match($pattern, $value);

    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function hasUppercaseLetter($value)
    {
        $pattern = '/[A-Z]/';
        return (bool) preg_match($pattern, $value);
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    public function hasSpecialChars($value)
    {
        $pattern = sprintf('/[%s]/',
            preg_quote('+-*=%&#_.,;:\§@€µ?$!{}[]()|'));

        return (bool) preg_match($pattern, $value);
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    public function hasDigits($value)
    {
        $pattern = '/[0-9]/';
        return (bool) preg_match($pattern, $value);
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    public function hasWhitespaces($value)
    {
        $pattern = '/[[:space:]]/';
        return (bool) preg_match($pattern, $value);
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    public function hasUmlaute($value)
    {
        $pattern = '/[äöüßÄÖÜÀÁáÂâÈèÉéÊêÙùÚúÇç]/';
        return (bool) preg_match($pattern, $value);
    }

     /**
     * @param string $value
      *
     * @return boolean
     */
    public function hasRepeatingChars($value)
    {
        $pattern = '/(.)\1\1/';
        return (bool) preg_match($pattern, $value);
    }

    /**
     * @param string $value
     *
     * @return boolean
     */
    public function hasRepeatingWords($value)
    {
        $pattern = '/([a-zA-Z]{3,})(.)*\1\z/';
        return (bool) preg_match($pattern, $value);
    }


}
