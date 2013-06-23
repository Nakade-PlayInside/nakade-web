<?php
namespace User\Form\Validator;

use Traversable;
use Zend\Validator\Exception;
use Nakade\Abstracts\AbstractNakadeValidator;


/**
 * Validating password against a list of common passwords.
 * 
 * The following additional option keys are supported:
 * 'commons'   => array of strings (list of common passwords)
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class CommonPassword extends AbstractNakadeValidator 
{
    
    const INVALID    = 'invalidType';
    const NO_COMMON  = 'NoCommonPassword';
    
    protected $commons;
    
    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID   => "Invalid type given. String expected",
        self::NO_COMMON => "Do not use common password: %value%.",
        
    );

     public function getTranslatedTemplate()
    {
        //just for translation using PoE
        $template = array(
            self::INVALID           => $this->translate(
                "Invalid type given. String expected."),
            self::NO_COMMON            => $this->translate(
                "Do not use common password: %value%."),
           
        );
        return $template;
    }
 
   
    /**
     * Sets validator options
     *
     * @param  string|Traversable $pattern
     * @throws Exception\InvalidArgumentException On missing 'pattern' parameter
     */
    public function __construct($options)
    {
       //@todo: Übersetzung
        
        if (!is_array($options)) {
            throw new Exception\InvalidArgumentException(
                "Invalid options provided to constructor"
            );
        }

        if (!array_key_exists('commons', $options)) {
            throw new Exception\InvalidArgumentException(
                "Missing option 'commons'"
            );
        }
       
        $this->setCommons($options['commons']);
        parent::__construct($options);
    }

   
    /**
     * Returns the common passwords option
     *
     * @return array
     */
    public function getCommons()
    {
        return $this->commons;
    }

    /**
     * Sets the common passwords  option
     *
     * @param  string $commons
     * @return PasswordComplexity Provides a fluent interface
     */
    public function setCommons($commons)
    {
        $this->commons = $commons;
        return $this;
    }

    /**
     * Returns true if and only if $value matches against the commons option
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
        
        //making all listed names strtolower
        $map = array_map('strtolower', $this->getCommons());
        
        if (in_array(strtolower($value), $map) ) {
             $this->error(self::NO_COMMON);
             return false;
        }
        

        return true;
    }
    
   
}

?>
