<?php
namespace Message\Form;

use \Zend\InputFilter\InputFilter;

/**
 * Class MessageForm
 *
 * @package Message\Form
 */
class MessageForm extends BaseForm
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
                'name' => 'receiver',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('recipient').":",
                    'empty_option' => $this->translate('Please choose'),
                    'value_options' => $this->getValueOptions(),
                ),
            )
        );

        //subject
        $this->add(
            array(
                'name' => 'subject',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('subject').':',
                ),
            )
        );

        //message
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'message',
                'options' => array(
                    'label' =>  $this->translate('message').":",
                ),
            )
        );

        $this->add($this->getButtonFieldSet());


    }

    private function getValueOptions()
    {
        $valueOptions = array();

        if ($this->hasRecipients()) {
            $valueOptions['opponents'] = array(
                'label' => $this->translate('My Opponents'),
                'options' =>  $this->getOpponents()
            );
        }
        $valueOptions['moderators'] = array(
            'label' => $this->translate('Moderators'),
            'options' => $this->getModerators(),
        );

        return $valueOptions;
    }

    /**
     * @return array
     */
    private function getOpponents()
    {
        $opponents = array();
        /* @var $user \User\Entity\User */
        foreach ($this->getRecipients() as $user) {
            $opponents[$user->getId()] = $user->getShortName();
        }
        asort($opponents);

        return $opponents;
    }

    /**
     * @return array
     */
    private function getModerators()
    {
        $moderators = array(
            'isAdmin' => $this->translate('Admin'),
            'isRef' => $this->translate('Referee'),
        );

        if($this->hasRecipients()) {
            $moderators['isLM'] = $this->translate('League Manager');
        }

        return $moderators;
    }



    /**
     * @return InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(
            array(
                'name' => 'subject',
                'required' => true,
                'allowEmpty' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name'    => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'max'  => '120',
                        )
                    ),
                )
            )
        );

        $filter->add(
            array(
                'name' => 'message',
                'required' => true,
                'allowEmpty' => false,
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
        return "messageForm";
    }

}

