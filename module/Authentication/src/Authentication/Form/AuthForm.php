<?php
namespace Authentication\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Authentication form with ReCaptcha, translation option and
 * csrf token.  
 */
class AuthForm extends Form
{
    protected $_captchaAdapter;
    protected $_csrfToken;
    protected $_translator;
    protected $_textDomain="default";
    protected $_filter=null;
    protected $_isCaptchaShowing = false;
    
    /**
     * Expecting an CaptchaAdapter and optional Translator and 
     * corresponding text domain. 
     * 
     * @param \Zend\Captcha\AdapterInterface $captchaAdapter
     * @param \Zend\I18n\Translator\Translator $translator
     * @param type $textDomain
     */
    public function __construct(
            CaptchaAdapter $captchaAdapter, 
            Translator $translator = null,
            $textDomain = null
            ) 
    {
        
        //form name is AuthForm
        parent::__construct($name='AuthForm');
        $this->_captchaAdapter = $captchaAdapter;
        
        
        if (null !== $translator) {
            $this->_translator = $translator;
        }
        
        if (null !== $textDomain) {
            $this->_textDomain = $textDomain;
        }
        
      //  $this->init();
    }

    /**
     * Setter for filtering the form input
     * @param \Zend\InputFilter\InputFilter $filter
     */
    public function setFilter(InputFilter $filter) 
    {
        $this->_filter = $filter;
    }
    
    /**
     * Setter for filtering the form input
     * @param \Zend\InputFilter\InputFilter $filter
     */
    public function setShowCaptcha($show) 
    {
        $this->_isCaptchaShowing = $show;
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
   
    /**
     * Initializing the form. Call this method for receiving the formular.
     * This enables toggling the Captcha 
     */
    public function init()
    {
               
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
        $captcha = new Element\Captcha('captcha');
        $captcha->setCaptcha($this->_captchaAdapter);
        $captcha->setOptions(
            array('label' => $this->translate('Please verify you are human.'))
        );
        
        //showing captcha
        if($this->_isCaptchaShowing) {
         
            $this->add($captcha);
        }   
        
        
        //cross-site scripting hash protection
        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )    
        );
       
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
