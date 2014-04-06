<?php
namespace League\Form;

use Nakade\Abstracts\AbstractForm;

class ScheduleForm extends AbstractForm
{

    /**
     * Constructor
     */
    public function __construct()
    {
         //form name is AuthForm
        parent::__construct();
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {


        //matchday course
        $this->add(
            array(
                'name' => 'course',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Matchday course:'),
                    'value_options' => array(1 => $this->translate('weekly matchday'),
                                             2 => $this->translate('single match weekly')
                        ),
                ),
                'attributes' => array(
                    'value'  => 1,

                )

            )
        );

        //matchday course
        $this->add(
            array(
                'name' => 'period',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Period:'),
                    'value_options' => array(1 => $this->translate('weekly'),
                        2 => $this->translate('fortnightly'),
                        3 => $this->translate('three weeks'),
                        4 => $this->translate('monthly'),
                    ),
                ),
                'attributes' => array(
                    'value'  => 2,

                )

            )
        );

        //startdate
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Date',
                'name' => 'startdate',
                'options' => array(
                    'label' => $this->translate('Start date'),
                    'format' => 'Y-m-d',
                ),
                'attributes' => array(
                     'min'   => date('Y-m-d'),
                     'step'  => '1',
                     'value' => date('Y-m-d')
                ),
            )
        );

        //time
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Time',
                'name' => 'starttime',
                'options' => array(
                    'label' => $this->translate('Start time'),
                ),
                'attributes' => array(
                     'step'  => '900',
                     'value' => '18:00'
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

    public function getFilter()
    {
        $filter = new \Zend\InputFilter\InputFilter();



        return $filter;
    }
}
?>
