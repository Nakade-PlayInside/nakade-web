<?php
namespace PhlyContact\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\I18n\Translator\Translator;

class ContactForm extends Form
{
    protected $captchaAdapter;
    protected $csrfToken;
    protected $translator;
    protected $text_domain;
   
    public function __construct($name = null, CaptchaAdapter $captchaAdapter = null, Translator $translator = null, $text_domain = null)
    {
        parent::__construct($name);

        if (null !== $captchaAdapter) {
            $this->captchaAdapter = $captchaAdapter;
        }
        
        if (null !== $translator) {
            $this->translator = $translator;
        }
      
        if (null !== $text_domain) {
            $this->text_domain = $text_domain;
        }
        
               
        $this->init();
    }

    private function translate($message, $textDomain = null, $locale = null)
    {
       
        if (null === $this->translator) {
            //@todo: Exception einfügen
            throw new Exception\RuntimeException('Translator has not been set');
        }
        if (null === $textDomain) {
            $textDomain = $this->text_domain;
        }
        
        return $this->translator->translate($message, $textDomain, $locale);
    
    }
   
    public function init()
    {
        $name = $this->getName();
        if (null === $name) {
            $this->setName('contact');
        }
        
              
        $this->add(array(
            'name' => 'from',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' =>  $this->translate('From:'),
            ),
        ));

        $this->add(array(
            'name'  => 'subject',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => $this->translate('Subject:'),
            ),
        ));


        $this->add(array(
            'name'  => 'body',
            'type'  => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => $this->translate('Your message:'),
            ),
        ));

        $captcha = new Element\Captcha('captcha');
        $captcha->setCaptcha($this->captchaAdapter);
        $captcha->setOptions(array('label' => $this->translate('Please verify you are human.')));
        $this->add($captcha);
		
        $this->add(new Element\Csrf('csrf'));

        $this->add(array(
            'name' => 'Send',
            'type'  => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' =>  $this->translate('Send'),

            ),
        ));
    }
}
