<?php
namespace Message\Form;

use Message\Form\Hydrator\ReplyHydrator;
use Message\Services\RepositoryService;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use \Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class ReplyForm
 *
 * @package Message\Form
 */
class ReplyForm extends BaseForm
{


    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

       $this->add(
            array(
                'name' => 'sendTo',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('receiver').':',
                ),
                'attributes' => array(
                    'readonly' =>   true,
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
        return "replyForm";
    }

}

