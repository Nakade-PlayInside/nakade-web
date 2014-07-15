<?php
namespace Authentication\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;
use Zend\Captcha\AdapterInterface ;

/**
 * Authentication form with Captcha
 *
 * @package Authentication\Form
 */
class AuthForm extends AbstractForm
{
    private $captcha;
    protected $showCaptcha = false;

    /**
     * @param AdapterInterface $captcha
     */
    public function __construct(AdapterInterface $captcha)
    {
        $this->captcha = $captcha;
        parent::__construct('LoginForm');
        $this->setInputFilter($this->getFilter());
        //$contact = new Contact();
        //$this->bind($contact);
    }

    /**
     * @param bool $show
     */
    public function setIsShowingCaptcha($show)
    {
        $this->showCaptcha = $show;
    }

    /**
     * getter
     * @return bool $isCaptchaShowing
     */
    public function isShowingCaptcha()
    {
        return $this->showCaptcha;
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
                    'label' =>  $this->translate('Username').':',
                ),
            )
        );

        //password
        $this->add(
            array(
                'name'  => 'password',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' =>  $this->translate('Password').':',
                ),
            )
        );


        //checkbox remember Me
        $this->add(
            array(
                'name'  => 'rememberMe',
                'type'  => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' =>  $this->translate('Remember?') .':',
                ),
                'attributes' => array(
                    'class' => 'checkbox',
                ),
            )
        );

        if ($this->isShowingCaptcha()) {
            $this->add(
                array(
                    'name'  => 'captcha',
                    'type'  => 'Zend\Form\Element\Captcha',
                    'options' => array(
                        'label' => $this->translate('Please verify you are human.'),
                        'captcha' => $this->captcha
                    ),
                )
            );
        }


        //cross-site scripting hash protection
        //this is handled by ZF2 in the background - no need for server-side
        //validation
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

    /**
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(
            array(
                'name'       => 'identity',
                'required'   => true,
                'filters'    => array(
                    array('name'    => 'StripTags'),
                ),
            )
        );

        $filter->add(
            array(
                'name'       => 'password',
                'required'   => true,
                'filters'    => array(
                    array('name'    => 'StripTags'),
                ),

            )
        );
        return $filter;
    }
}
