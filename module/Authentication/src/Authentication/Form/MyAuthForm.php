<?php
namespace Authentication\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\I18n\Translator\Translator;

class MyAuthForm extends Form
{
    protected $_captchaAdapter;
    protected $_csrfToken;
    protected $_translator;
    protected $_textDomain="Auth";
   
    public function __construct(
            //$name = null, 
            //CaptchaAdapter $captchaAdapter = null, 
            Translator $translator = null, 
            $textDomain = null)
    {
        parent::__construct($name='AuthForm');

       /* if (null !== $captchaAdapter) {
            $this->_captchaAdapter = $captchaAdapter;
        }*/
        
        if (null !== $translator) {
            $this->_translator = $translator;
        }
              
        $this->init();
    }

    private function translate($message, $locale = null)
    {
       
        if (null === $this->_translator) {
           return $message;
        }
       
        
        return $this->_translator->translate(
                $message, 
                $this->_textDomain, 
                $locale
                );
    
    }
   
    public function init()
    {
        $name = $this->getName();
        if (null === $name) {
            $this->setName('AuthForm');
        }
        
        //identity
        $this->add(
            array(
                'name' => 'identity',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                'label' =>  $this->translate('Username:'),
                ),
            )
        );

        //password
        $this->add(
            array(
                'name'  => 'password',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                'label' =>  $this->translate('Password:'),
                ),
            )
        );


        //checkbox remember Me
        $this->add(
            array(
                'name'  => 'rememberme',
                'type'  => 'Zend\Form\Element\Checkbox',
                'options' => array(
                'label' =>  $this->translate('Remember Me ?:'),
                ),
            )
        );

        //captcha
        /*
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
        */

        //submit button
        $this->add(
            array(
                'name' => 'Send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                'value' =>   $this->translate('Submit'),

            ),
        )
        );
    }
}
