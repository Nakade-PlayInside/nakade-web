<?php

namespace User\Factory;

use Traversable;
use User\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Creates a translated mail for sending verification mails.
 * Use the fabric method for getting the mailclass required.
 */
class MailFactory implements FactoryInterface
{
    
    protected $_emailOptions;
    protected $_transport;
    protected $_message;
    protected $_translator;
    protected $_textDomain;
    
    /**
     * implemented ServiceLocator
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $services
     * @return \User\Services\MailFactory
     */
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        //configuration 
        $this->_textDomain = isset($config['User']['text_domain']) ? 
            $config['User']['text_domain'] : null;
        
        //email
        $this->_emailOptions = isset($config['User']['email_options']) ? 
            $config['User']['email_options'] : null;
       
        //optional translator
        $this->_translator = $services->get('translator');
        
        //mandatory
        $this->_transport = $services->get('MailTransport');
        $this->_message   = $services->get('MailMessage');
        
        return $this;
    }
    
    /**
     * fabric method for getting the mail needed. expecting the mail name as
     * string. Throws an exception if provided typ is unknown.
     * Typ: - 'verify'   => new users
     *      - 'password' => reset password
     * 
     * @param string $typ
     * @return string
     * @throws RuntimeException
     */
    public function getMail($typ)
    {
       
        switch (strtolower($typ)) {
           
           case "verify":   $mail = new Mail\VerifyMail($this->_emailOptions);
                            break;
               
           case "password": $mail = new Mail\PasswordMail($this->_emailOptions);
                            break;
                    
                        
           default      :   throw new RuntimeException(
                sprintf('An unknown mail type was provided.')
           );              
        }
        
        $mail->setMessage($this->_message);
        $mail->setTransport($this->_transport);
        $mail->setTranslator($this->_translator, $this->_textDomain);
        
        return $mail;
    }      
    
}
