<?php

namespace User\Services;

use Traversable;
use User\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Creates the form with a translator, filter and validator.
 * Adds the translation file for validation messages from zend ressources.
 */
class MailFactory implements FactoryInterface
{
    
    protected $emailOptions;
    protected $transport;
    protected $message;
    protected $translator;
    protected $textDomain;
    
    public function createService(ServiceLocatorInterface $services)
    {
        $config  = $services->get('config');
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        
        //configuration 
        $this->textDomain = isset($config['User']['text_domain']) ? 
            $config['User']['text_domain'] : null;
        
        //email
        $this->emailOptions = isset($config['User']['email_options']) ? 
            $config['User']['email_options'] : null;
       
        //optional translator
        $this->translator = $services->get('translator');
        
        //mandatory
        $this->transport = $services->get('MailTransport');
        $this->message   = $services->get('MailMessage');
        
        return $this;
    }
    
    /**
     * receives the points of the provided game statistics
     * @param string $typ
     * @return string
     * @throws RuntimeException
     */
    public function getMail($typ)
    {
       
        switch (strtolower($typ)) {
           
           case "verify":   $mail = new Mail\VerifyMail($this->emailOptions);
                            break;
               
           case "password": $mail = new Mail\PasswordMail($this->emailOptions);
                            break;
                    
                        
           default      :   throw new RuntimeException(
                sprintf('An unknown mail type was provided.')
           );              
        }
        
        $mail->setMessage($this->message);
        $mail->setTransport($this->transport);
        $mail->setTranslator($this->translator, $this->textDomain);
        var_dump($this->textDomain);
        return $mail;
    }      
    
}
