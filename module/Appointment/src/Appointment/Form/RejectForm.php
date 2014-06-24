<?php
namespace Appointment\Form;

use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;
use Appointment\Entity\Appointment;
/**
 * Class RejectForm
 *
 * @package Appointment\Form
 */
class RejectForm extends AbstractForm
{

    /**
     * constructor
     * for using this form you have to init and setFilter OR you bind entity
     */
    public function __construct()
    {
        //form name
        parent::__construct('RejectForm');
        $this->setInputFilter($this->getFilter());
    }

    /**
     * @param Appointment $object
     */
    public function bindEntity($object)
    {
        $this->bind($object);
        $this->init();
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
       // 'Y-m-d\TH:iP'

        //recipient
        $this->add(
            array(
                'name' => 'reason',
                'type' => 'Zend\Form\Element\Textarea',
                'options' => array(
                    'label' =>  $this->translate('Reject Reason').":",
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
                'name' => 'reject',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Reject'),

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
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(

            array(
                'name' => 'reason',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'NotEmpty'),
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '600',
                        )
                    ),
                )
            )
        );

        return $filter;
    }

}

