<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;

class ConfirmForm extends AbstractForm
{

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct('QuestionForm');
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

        //cancel button
        $this->add(
            array(
                'name' => 'cancel',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array('value' => $this->translate('Cancel'))
            )
        );

        //yes
        $this->add(
            array(
                'name' => 'submit',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array('value' => $this->translate('YES'))
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

