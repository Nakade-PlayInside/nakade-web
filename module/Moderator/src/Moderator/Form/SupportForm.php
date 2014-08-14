<?php
namespace Moderator\Form;

use Moderator\Form\Hydrator\SupportHydrator;
use Zend\InputFilter\InputFilter;
/**
 * Class SupportForm
 *
 * @package Moderator\Form
 */
class SupportForm extends BaseForm implements SupportInterface
{

    public function init()
    {
        $this->add(
            array(
            'name' => self::SUBJECT,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' =>  $this->translate('Subject') . ':',
                'value_options' => array (
                    1 => $this->translate('Appointment'),
                    2 => $this->translate('Result'),
                    3 => $this->translate('Other'),
                ),
                'empty_option'  => '-- ' . $this->translate('choose') . ' --'
                ),
            )
        );

        //association
        $this->add(
            array(
                'name' => self::ASSOCIATION,
                'type' => 'Zend\Form\Element\Hidden'
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
        return $filter;
    }

    /**
     * @return SupportHydrator
     */
    protected function initHydrator()
    {
        $hydrator = new SupportHydrator($this->getEntityManager(), $this->getAuthenticationService());
        return $hydrator;
    }

    /**
     * @return string
     */
    public function getFormName()
    {
        return 'SupportForm';
    }
}
