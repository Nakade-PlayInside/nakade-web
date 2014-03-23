<?php
namespace Message\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Message\Entity\Message;
use \Zend\InputFilter\InputFilter;

/**
 * Form for making a new league
 */
class ReplyForm extends AbstractForm
{
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name= $name;

        //form name
        parent::__construct('ReplyForm');

        $this->setObject(new Message());
        $this->setHydrator(new Hydrator());
        $this->init();
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {


        //recipient JUST FOR INFORMATION
        //users are taken from entity
        $this->add(
            array(
                'name' => 'name',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('receiver').':',

                ),
                'attributes' => array(
                    'readonly' =>   true,
                    'value' => $this->name
                ),
            )
        );

        //subject
        $this->add(
            array(
                'name' => 'subject',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('subject').':',
                ),

            )
        );

        //message
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'message',
                'options' => array(
                    'label' =>  $this->translate('message').":",
                ),
            )
        );

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

        //cancel button
        $this->add(
            array(
                'name' => 'cancel',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Cancel'),

                ),
            )
        );

    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(
            array(
                 'name' => 'subject',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
                           'options' => array (
                                  'encoding' => 'UTF-8',
                                  'max'  => '120',
                           )
                     ),
                  )
            )
        );

        $filter->add(
            array(
                'name' => 'message',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                ),

            )
        );

        return $filter;
    }

}

