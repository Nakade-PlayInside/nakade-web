<?php
namespace User\Mail;

use Nakade\Abstracts\AbstractMail;
use Zend\Mime;

/**
 * Description of Mail
 *
 * @author Dr.Holger Maerz <holger@nakade.de>
 */
class VerifyMail extends AbstractMail {
   
    
    const SALUTATION   = 'emailSalutation';
    const ACCOUNT      = 'emailAccount';
    const VERIFY       = 'emailVerify';
    const GREETING     = 'emailGreeting';
    const CONTACT      = 'emailContact';
    const LINK         = 'emailActivation';
    const SUBJECT      = 'emailSubject';
   
    /**
     * @var array
     */
    protected $_mailTemplates = array(
        self::SALUTATION => "Welcome %firstname%,\n\nThank you for your registration at nakade.de\n\n",
        self::ACCOUNT    => "Your Credentials:\n\nusername: %username%\npassword: %password%\n\n",
        self::VERIFY     => "\nYour account requires activation during the next %expire% hours. Please click on the link for activation. If you fail to activate your account in time, you have to reset your password using the \"forgot password\" option.\n",
        self::GREETING   => "\nMay the stones be with you\n\nYour %signature%\n",
        self::CONTACT    => "\n\n%club%\nCourt of Registration: %register_court%\nRegister No.: %register_no%",
        self::LINK       => "\n\n%activationLink%\n\n",
        self::SUBJECT    => "%prefix% - Your Credentials",
    );
    
    
    /**
     * @var array
     */
    protected $_mailVariables = array(
        'firstname' => 'firstname',
        'username'  => 'username',
        'password'  => 'password',
        'expire'    => 'expire',
        'signature' => 'signature',
        'club'      => 'club',
        'register_court'  => 'register_court',
        'register_no'      => 'register_no',
        'activationLink'   => 'activationLink',
        'prefix'    => 'prefix',
    );
    
    protected $firstname;
    protected $username;
    protected $password;
    protected $verifyString;
    protected $verifyUrl="http://www.nakade.de";
    protected $activationLink;
    
    protected $expire='72';
    protected $prefix = 'Nakade';
    protected $signature='Nakade Team';
    protected $club='Berliner Baduk Club e.V.'; 
    protected $register_court='Berlin-Charlottenburg';
    protected $register_no='VR31852';
    
    /**
     * options :
     *  - expire -> hours
     *  - prefix -> subject prefix
     *  - signature -> email signature
     *  - club -> contact name
     *  - register_court => Amtsgericht
     *  - register_no    => Registriernummer
     * 
     * @param \User\Mail\Traversable $options
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($options=null) {
        
        if (!is_array($options) && !$options instanceof Traversable) {
            throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable');
        }
        
        foreach ($options as $name => $option) {
            if(property_exists($this, $name))
                $this->$name = $option;    
        }
      
    }    
    
    
    public function getTranslatedMailTemplate()
    {
        //just for translation using PoE
        $salutation = sprintf("%s,\n\n%s\n\n",
            $this->translate("Welcome %firstname%"),
            $this->translate("Thank you for your registration at nakade.de")
        );
        
        $account = sprintf("%s:\n\n%s: %%username%%\n%s: %%password%%\n\n",
            $this->translate("Your Credentials"),
            $this->translate("username"),
            $this->translate("password")    
        );
        
        $verify = sprintf("\n%s %s %s\n",
            $this->translate("Your account requires activation during the next %expire% hours."),
            $this->translate("Please click on the link for activation."),
            $this->translate("If you fail to activate your account in time, you have to reset your password using the 'forgot password' option.")
        );
        
        $greeting = sprintf("\n%s\n\n%s\n",
            $this->translate("May the stones be with you."),
            $this->translate("Your %signature%.")    
        );
        
        $contact = sprintf("\n\n%%club%%\n%s: %%register_court%%\n%s: %%register_no%%",
            $this->translate("Court of Registration"),
            $this->translate("Register No.")    
        );
        
        $subject = $this->translate("%prefix% - Your Credentials");
        $this->_mailTemplates[self::SUBJECT] = $subject;
        
        $template = array(
            self::SALUTATION => $salutation,
            self::ACCOUNT    => $account,
            self::VERIFY     => $verify,
            self::GREETING   => $greeting,
            self::CONTACT    => $contact,
            self::LINK       => "\n\n%activationLink%\n\n",
            self::SUBJECT    => $subject,
        );
        
        return $template;
        
    }


    public function setData($data)
    {
        
        $this->verifyUrl = isset($data['verifyUrl']) ? 
                $data['verifyUrl'] : null;
        
         $this->verifyString = isset($data['verifyString']) ? 
                $data['verifyString'] : null;
         
        $this->firstname = isset($data['firstname'])? $data['firstname'] : null;
        $this->username  = isset($data['username']) ? $data['username'] : null;
        $this->password  = isset($data['generated']) ? $data['generated'] : null;
        
        $email = isset($data['email']) ? $data['email'] : null;
        $this->activationLink = sprintf('%s/user/verify?email=%s&verify=%s',
            $this->verifyUrl,    
            $email,
            $this->verifyString    
        );
        
       
    }
   
    /**
     * Replaces all placeholder with property values.
     *
     * @param string $message
     * @return string
     */
    protected function setPlaceholder($message)
    {
        foreach ($this->_mailVariables as $ident => $property) {
            $value = $this->$property;
            $message = str_replace("%$ident%", (string) $value, $message);
        }
        
        return $message;
    }
    
    
    /**
     * get the mail subject
     * 
     * @return string
     */
    public function getSubject()
    {
        $temp    = $this->_mailTemplates[self::SUBJECT];
        $subject = $this->translate($temp);
        
        return $this->setPlaceholder($subject);
    }
    
    protected function createMail($template=null)
    {
       
        if(null===$template)
            $template = $this->_mailTemplates;
        
        $message  = "";
        $message .= $template[self::SALUTATION];
        $message .= $template[self::ACCOUNT];
        $message .= $template[self::VERIFY];
        $message .= $template[self::LINK];
        $message .= $template[self::GREETING];
        $message .= $template[self::CONTACT];
       
        return $this->setPlaceholder($message);
        
    }
    
    /**
     * get the mail body
     * @return \Zend\Mime\Message
     */
    public function getBody()
    {
        // $rawbody       = $this->createMail();
        // $text = $this->getTextMessage($rawbody);
         
         $template    =  $this->getTranslatedMailTemplate();
         $translation = $this->createMail($template);
         $html = $this->getHTMLMessage($translation);
         
         $body = new Mime\Message;
         $body->setParts(array($html));
         
         return $body;
    }
    
    
    
    

}

?>
