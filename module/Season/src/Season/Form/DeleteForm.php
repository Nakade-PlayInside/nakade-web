<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;

class DeleteForm extends AbstractForm implements WeekDayInterface
{

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct('DeleteForm');
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
                'name' => 'delete',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array('value' => $this->translate('Delete'))
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

