<?php
namespace Appointment\Form;

use \Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
/**
 * Class AppointmentForm
 *
 * @package Appointment\Form
 */
class AppointmentForm extends BaseForm
{

    /**
     * init the form. It is necessary to call this function
     * before using the form.
     */
    public function init()
    {

        //info
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => self::FIELD_OLD_DATE,
                'options' => array('label' => $this->translate('Old date') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //date
        $this->add(
            array(
                'name' => self::FIELD_DATE,
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' =>  $this->translate('New Match Date').":",
                    'format' => 'Y-m-d'
                ),
                'attributes' => array(
                    'min'  => \date('Y-m-d'),
                    'step' => '1', // days; default step interval is 1 day
                )

            )
        );

        $this->add(array(
            'type' => 'Zend\Form\Element\Time',
            'name' => self::FIELD_TIME,
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
                'name' => self::FIELD_CONFIRM_USER,
                'options' => array(
                    'label' => $this->translate('I do confirm my opponent has agreed to this schedule'),
                    'use_hidden_element' => true,
                    'checked_value' => 1,
                    'unchecked_value' => 'no',
                ),
                'attributes' => array('class' => 'checkbox'),
            )
        );

        $this->add($this->getButtonFieldSet());

    }

    /**
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(
            array(
                'name' => self::FIELD_CONFIRM_USER,
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'break_chain_on_failure' => true,
                        'options' => array(
                            'messages' => array(
                                Digits::NOT_DIGITS =>
                                    $this->translate('You must confirm your opponent agreed to the new schedule.'),
                            ),
                        ),
                    ),
                ),
            )
        );


        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return "appointmentForm";
    }

}

