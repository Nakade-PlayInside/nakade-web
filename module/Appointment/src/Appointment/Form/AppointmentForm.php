<?php
namespace Appointment\Form;

use Appointment\Entity\Appointment;
use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;
use \Zend\Validator\Identical;

/**
 * Class AppointmentForm
 *
 * @package Appointment\Form
 */
class AppointmentForm extends AbstractForm
{

    const USER_CONFIRM = "1";

    /* @var $maxDate \DateTime */
    private $maxDate;

    /**
     * @param int        $maxDatePeriod
     */
    public function __construct($maxDatePeriod=4)
    {

        //form name
        parent::__construct('AppointmentForm');

        $period = sprintf('+%d weeks', $maxDatePeriod);
        $this->maxDate = new \DateTime();
        $this->maxDate->modify($period);
    }

    /**
     * @param Appointment $object
     */
    public function bindEntity(Appointment $object)
    {
        $this->bind($object);

        $this->maxDate = $object->getOldDate();
        $this->maxDate->modify('+1 months');

        $this->init();
        $this->setInputFilter($this->getFilter());
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
                'name' => 'date',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' =>  $this->translate('New Match Date').":",
                    'format' => 'Y-m-d'
                ),
                'attributes' => array(
                    'min'  => \date('Y-m-d'),
                    'max'  => $this->maxDate->format('Y-m-d'),
                    'step' => '1', // days; default step interval is 1 day
                )

            )
        );

        $this->add(array(
            'type' => 'Zend\Form\Element\Time',
            'name' => 'time',
            'options'=> array(
                'label' => $this->translate('Time').":"
            ),
            'attributes' => array(
                'min' => '00:00:00',
                'max' => '23:59:59',
                'step' => '900', // seconds; default step interval is 60 seconds
                'value' => '18:30:00'
            )
        ));

        //check
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'confirming',
                'options' => array(
                    'label' => $this->translate('I do confirm my opponent has agreed to this schedule'),
                    'use_hidden_element' => true,
                    'checked_value' => self::USER_CONFIRM,
                    'unchecked_value' => 'no'

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
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(

            array(
                'name' => 'confirming',
                'required' => true,
                'filters'  => array(),
                'validators' => array(
                    array(
                        'name'    => 'Zend\Validator\Identical',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'token' => self::USER_CONFIRM,
                            'messages' => array(
                                Identical::NOT_SAME => $this->translate(
                                    'You must confirm your opponent agreed to the new schedule.'
                                ),
                            ),
                        ),
                    ),
                ),
            )
        );

        return $filter;
    }

}

