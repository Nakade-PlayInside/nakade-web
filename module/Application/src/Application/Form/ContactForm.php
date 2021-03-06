<?php
namespace Application\Form;

use Application\Entity\Contact;
use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;
use Zend\Validator\Hostname as HostnameValidator;
use Zend\Captcha\AdapterInterface ;

/**
 * Class ContactForm
 *
 * @package Application\Form
 */
class ContactForm extends AbstractForm
{

    private $captcha;

    /**
     * @param AdapterInterface $captcha
     */
    public function __construct(AdapterInterface $captcha)
    {
        $this->captcha = $captcha;
        parent::__construct('ContactForm');
        $this->filter = $this->getFilter();
        $contact = new Contact();
        $this->bind($contact);
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        $this->add(
            array(
                'name' => 'name',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Your Name') . ':',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'email',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Your Email') . ':',
                ),
            )
        );

        $this->add(
            array(
                'name'  => 'message',
                'type'  => 'Zend\Form\Element\Textarea',
                'options' => array(
                    'label' => $this->translate('Your message') . ':',
                ),
            )
        );

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
                'name' => 'name',
                'filters'  => array(
                    array('name' => 'Alnum',
                          'options' => array (
                              'allowWhiteSpace' => true
                          )
                    ),
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(),

            )
        );

        $filter->add(

            array(
                'name' => 'email',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringToLower'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'NotEmpty',
                    ),
                    array(
                        'name'    => 'EmailAddress',
                        'options' => array(
                            'allow'  => HostnameValidator::ALLOW_DNS,
                            'domain' => true,
                        ),
                    ),
                ),

            )
        );

        $filter->add(

            array(
                'name' => 'message',
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'NotEmpty',
                    ),
                ),

            )
        );

        return $filter;
    }

}

