<?php
namespace Appointment\Form;

use Nakade\Abstracts\AbstractForm;
use \Zend\InputFilter\InputFilter;
use \Zend\I18n\Translator\Translator;
use \Zend\Validator\Identical;

class RejectForm extends AbstractForm
{

    const USER_CONFIRM = "1";
    private $endOfSeason;

    /**
     * @param \DateTime  $endOfSeason
     *
     * @param Translator $translator
     */
    public function __construct($endOfSeason, Translator $translator = null)
    {

        //form name
        parent::__construct('RejectForm');

        $this->setTranslator($translator);
        $this->setTranslatorTextDomain('Appointment');

        if ($endOfSeason instanceof \DateTime) {
            $this->endOfSeason = $endOfSeason;
        } else {
            $this->endOfSeason = new \DateTime();
            $this->endOfSeason->modify('+3 month');
        }

        $this->init();
        $this->setInputFilter($this->getFilter());
    }


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
       // 'Y-m-d\TH:iP'

        //recipient
        $this->add(
            array(
                'name' => 'rejectReason',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Reject Reason').":",
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

    /**
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
        return $filter;
    }

}

