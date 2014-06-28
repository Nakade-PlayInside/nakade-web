<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;

class CreateForm extends AbstractForm implements WeekDayInterface
{

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct('CreateForm');
        $this->setInputFilter($this->getFilter());
        $this->init();
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array('csrf_options' => array('timeout' => 600))
            )
        );

        //delete
        $this->add(
            array(
                'name' => 'create',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array('value' => $this->translate('Create'))
            )
        );

        //cancel button
        $this->add(
            array(
                'name' => 'cancel',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array('value' => $this->translate('Cancel'))
            )
        );
    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
        return $filter;
    }


}

