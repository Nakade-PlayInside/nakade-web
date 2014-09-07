<?php
namespace Appointment\Form;

use \Zend\InputFilter\InputFilter;
/**
 * Class ConfirmForm
 *
 * @package Appointment\Form
 */
class ConfirmForm extends BaseForm
{

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
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
                'name' => self::FIELD_CONFIRM_APPOINTMENT,
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Confirm'),
                    'class' => 'btn btn-success actionBtn'
                ),
            )
        );

        //cancel button
        $this->add(
            array(
                'name' => self::FIELD_REJECT_APPOINTMENT,
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Reject'),
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
        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return "confirmForm";
    }

}

