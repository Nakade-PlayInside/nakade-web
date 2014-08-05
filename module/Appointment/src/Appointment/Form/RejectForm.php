<?php
namespace Appointment\Form;

use \Zend\InputFilter\InputFilter;
/**
 * Class RejectForm
 *
 * @package Appointment\Form
 */
class RejectForm extends BaseForm
{

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        //recipient
        $this->add(
            array(
                'name' => self::FIELD_REJECT_REASON,
                'type' => 'Zend\Form\Element\Textarea',
                'options' => array(
                    'label' =>  $this->translate('Reject Reason').":",
                ),
            )
        );

        $this->add($this->getButtonFieldSet());
    }

    /**
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(

            array(
                'name' => self::FIELD_REJECT_REASON,
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'NotEmpty'),
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '600',
                        )
                    ),
                )
            )
        );

        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return "rejectForm";
    }

}

