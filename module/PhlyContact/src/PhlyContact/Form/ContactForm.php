<?php
namespace PhlyContact\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\I18n\Translator\Translator;

class ContactForm extends Form
{
    protected $_captchaAdapter;
    protected $_csrfToken;
    protected $_translator;
    protected $_textDomain;
   
    public function __construct($name = null, 
            CaptchaAdapter $captchaAdapter = null, 
            Translator $translator = null, 
            $textDomain = null)
    {
        parent::__construct($name);

        if (null !== $captchaAdapter) {
            $this->_captchaAdapter = $captchaAdapter;
        }
        
        if (null !== $translator) {
            $this->_translator = $translator;
        }
      
        if (null !== $textDomain) {
            $this->_textDomain = $textDomain;
        }
        
               
        $this->init();
    }

    private function translate($message, $textDomain = null, $locale = null)
    {
       
        if (null === $this->_translator) {
            //@todo: Exception einfügen
            throw new Exception\RuntimeException('Translator has not been set');
        }
        if (null === $textDomain) {
            $textDomain = $this->_textDomain;
        }
        
        return $this->_translator->translate($message, $textDomain, $locale);
    
    }
   
    public function init()
    {
        $name = $this->getName();
        if (null === $name) {
            $this->setName('contact');
        }
        
              
        $this->add(
            array(
            'name' => 'from',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' =>  $this->translate('From:'),
            ),
        )
        );

        $this->add(
            array(
            'name'  => 'subject',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $this->translate('Subject:'),
            ),
        )
        );


        $this->add(
            array(
            'name'  => 'body',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => $this->translate('Your message:'),
            ),
        )
        );

        $captcha = new Element\Captcha('captcha');
        $captcha->setCaptcha($this->_captchaAdapter);
        $captcha->setOptions(
            array('label' => $this->translate(
                'Please verify you are human.'
            )
            )
        );
        $this->add($captcha);
        $this->add(new Element\Csrf('csrf'));

        $this->add(
            array(
                'name' => 'Send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                'value' =>  $this->translate('Send'),

            ),
        )
        );
    }
}
