<?php
namespace Appointment\Form;

use Appointment\Entity\Appointment;
use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;
use \Zend\Validator\Identical;
use Season\Services\SeasonFieldsetService;

/**
 * Class AppointmentForm
 *
 * @package Appointment\Form
 */
class AppointmentForm extends AbstractForm
{

    const USER_CONFIRM = "1";

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {

        //form name
        parent::__construct('AppointmentForm');
        $this->service = $service;
    }

    /**
     * @param Appointment $object
     */
    public function bindEntity($object)
    {
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);

    }


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
                'name' => 'oldDate',
                'options' => array('label' => $this->translate('Old date') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        $this->add(
            array(
                'name' => 'submitterId',
                'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(
            array(
                'name' => 'responderId',
                'type' => 'Zend\Form\Element\Hidden',
        ));

        //date
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
                    'unchecked_value' => 'no',
                ),
                'attributes' => array(
                    'class' => 'checkbox',
                )
            )
        );

        $this->add($this->getService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET));

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

    /**
     * @return SeasonFieldsetService
     */
    public function getService()
    {
        return $this->service;
    }

}

