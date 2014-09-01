<?php
namespace Authentication\Form;

use Authentication\Form\Hydrator\LoginHydrator;
use Nakade\Abstracts\AbstractForm;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\InputFilter;
use \Authentication\Session\FailureContainer;

/**
 * Authentication form with Captcha
 *
 * @package Authentication\Form
 */
class AuthForm extends AbstractForm implements AuthInterface
{
    private $session;
    private $captcha;

    /**
     * @param mixed            $captcha
     * @param FailureContainer $session
     */
    public function __construct($captcha,FailureContainer $session)
    {
        parent::__construct($this->getFormName());

        $this->captcha = $captcha;
        $this->session = $session;

        $hydrator = new LoginHydrator();
        $this->setHydrator($hydrator);

        $this->setInputFilter($this->getFilter());
    }

    /**
     * @param \Authentication\Entity\Login $object
     */
    public function bindEntity($object)
    {
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }

    /**
     * init
     */
    public function init()
    {

        //identity
        $this->add(
            array(
                'name' => self::IDENTITY,
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Username').':',
                ),
            )
        );

        //password
        $this->add(
            array(
                'name'  => self::PASSWORD,
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' =>  $this->translate('Password').':',
                ),
            )
        );


        //checkbox remember Me
        $this->add(
            array(
                'name'  => self::REMEMBER,
                'type'  => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' =>  $this->translate('Remember?'),
                ),
                'attributes' => array(
                    'class' => 'checkbox',
                ),
            )
        );

        if ($this->hasCaptcha()) {
            $this->add(
                array(
                    'name'  => 'captcha',
                    'type'  => 'Zend\Form\Element\Captcha',
                    'options' => array(
                        'label' => $this->translate('Please verify you are human.'),
                        'captcha' => $this->getCaptcha(),
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
                    'class' => 'btn btn-success actionBtn'

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
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 50
                        )
                    ),
                )
            )
        );

        $filter->add(
            array(
                'name'       => 'password',
                'required'   => true,
                'filters'    => array(
                    array('name'    => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max' => 80
                        )
                    ),
                )

            )
        );
        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'LoginForm';
    }

    /**
     * @return \Authentication\Session\FailureContainer
     */
    private function hasCaptcha()
    {
        return ($this->getSession()->hasExceededAllowedAttempts());
    }


    /**
     * @return FailureContainer
     */
    private function getSession()
    {
        return $this->session;
    }

    /**
     * @return \Nakade\Services\NakadeCaptchaFactory
     */
    private function getCaptcha()
    {
        return $this->captcha;
    }


}
