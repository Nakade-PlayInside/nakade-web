<?php
namespace Support\Form;

use Support\Form\Hydrator\MessageHydrator;
use Zend\InputFilter\InputFilter;
/**
 * Class MailForm
 *
 * @package Support\Form
 */
class MailForm extends BaseForm implements SupportInterface
{

    public function init()
    {

        $this->add(
            array(
                'name' => self::STAGE,
                'type' => 'Zend\Form\Element\Hidden',
            )
        );

        $this->add(
            array(
                'name' => self::SUBJECT,
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Subject').":",
                ),
                'attributes' => array(
                    'readonly' =>   true,
                ),
            )
        );

        //message
        $this->add(
            array(
                'name' => self::MESSAGE,
                'type' => 'Zend\Form\Element\Textarea',
                'options' => array(
                    'label' =>  $this->translate('Message').":",
                ),
            )
        );

        $this->add($this->getButtonFieldSet());
    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();
        $filter->add(
            array(
                'name' => self::MESSAGE,
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
     * @return MessageHydrator
     */
    protected function initHydrator()
    {
        $hydrator = new MessageHydrator($this->getEntityManager(), $this->getAuthenticationService());
        return $hydrator;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'MailForm';
    }
}
