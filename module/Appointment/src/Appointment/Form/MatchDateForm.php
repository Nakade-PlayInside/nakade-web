<?php
namespace Appointment\Form;

use \Zend\InputFilter\InputFilter;
/**
 * Class MatchDateForm
 *
 * @package Appointment\Form
 */
class MatchDateForm extends BaseForm
{

    /**
     * init the form. It is necessary to call this function
     * before using the form.
     */
    public function init()
    {

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Hidden',
                'name' => self::FIELD_MODERATOR_APPOINTMENT
            )
        );

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

        $this->add($this->getButtonFieldSet());

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
        return "matchDateForm";
    }

}

