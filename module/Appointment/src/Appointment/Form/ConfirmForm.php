<?php
namespace Appointment\Form;

use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;
use \Zend\I18n\Translator\Translator;
use \Zend\Validator\Identical;

class ConfirmForm extends AbstractForm
{

    /**
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator = null)
    {

        //form name
        parent::__construct('ConfirmForm');

        $this->setTranslator($translator);
        $this->setTranslatorTextDomain('Appointment');

        $this->init();
        $this->setInputFilter($this->getFilter());
    }


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
                'name' => 'confirm',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Confirm'),

                ),
            )
        );

        //cancel button
        $this->add(
            array(
                'name' => 'reject',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Reject'),

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

}

