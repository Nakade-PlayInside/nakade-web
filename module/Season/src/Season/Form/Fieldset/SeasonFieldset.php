<?php
namespace Season\Form\Fieldset;

use Nakade\Abstracts\AbstractFieldset;

/**
 * Class SeasonFieldset
 *
 * @package Season\Form\Fieldset
 */
class SeasonFieldset extends AbstractFieldset
{

    /**
     * construct
     */
    public function __construct()
    {
        //form name is SeasonForm
        parent::__construct('season');
        $this->init();
    }



    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        //association
        $this->add(
            array(
                'name' => 'associationName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Association') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //number
        $this->add(
            array(
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season no.') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //date
        $this->add(
            array(
                'name' => 'startDate',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' =>  $this->translate('Start date').":",
                    'format' => 'Y-m-d'
                ),
                'attributes' => array(
                    'min'  => \date('Y-m-d'),
                    'step' => '1', // days; default step interval is 1 day
                )

            )
        );

        //winPoints
        $this->add(
            array(
                'name' => 'winPoints',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Winning points') . ':',
                    'value_options' => array (
                        1 => '1',
                        2 => '2',
                        3 => '3'
                    )
                ),
            )
        );

        //komi
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'komi',
                'options' => array('label' =>  $this->translate('Komi') . ':'),
            )
        );

    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}
