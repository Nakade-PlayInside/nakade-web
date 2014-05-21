<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use League\Entity\Match;
/**
 * Form for changing a matchday
 */
class MatchdayForm extends AbstractForm
{


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setObject(new Match());
        $this->setHydrator(new Hydrator());
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->setAttribute('method', 'post');

        //date
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Date',
                'name' => 'date',
                'options' => array(
                    'label' => $this->translate('Date'),
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
                'name' => 'time',
                'options' => array(
                    'label' => $this->translate('Time'),
                ),
                'attributes' => array(
                     'step'  => '900',

                ),
            )
        );

        //check
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'changeColors',
                'options' => array(
                    'label' => $this->translate('Change Colors'),
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

         //submit
        $this->add(
            array(
                'name' => 'submit',
                'attributes' => array(
                    'type'  => 'submit',
                    'value' => $this->translate('Go'),
                    'id' => 'submitbutton',
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
        $filter = new \Zend\InputFilter\InputFilter();



        return $filter;
    }
}
