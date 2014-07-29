<?php
namespace User\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Class InviteFriendForm
 *
 * @package User\Form
 */
class InviteFriendForm extends BaseForm
{

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {
        $this->add($this->getUserFieldFactory()->getField(self::FIELD_EMAIL));

        //message
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'message',
                'options' => array(
                    'label' =>  $this->translate('Message (opt.)') . ":",
                ),
            )
        );
    }

    /**
     * get the InputFilter
     *
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {

        $filter = new InputFilter();
        $filter->add($this->getUserFilterFactory()->getFilter(self::FIELD_EMAIL));

        $filter->add(
            array(
                'name' => 'message',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                ),

            )
        );
        return $filter;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'InviteFriendForm';
    }

}
