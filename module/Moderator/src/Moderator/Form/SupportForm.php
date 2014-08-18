<?php
namespace Moderator\Form;

use Moderator\Entity\SubjectInterface;
use Moderator\Form\Hydrator\SupportHydrator;
use Zend\InputFilter\InputFilter;
/**
 * Class SupportForm
 *
 * @package Moderator\Form
 */
class SupportForm extends BaseForm implements SupportInterface, SubjectInterface
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
                    self::SUBJECT_APPOINTMENT => $this->translate('Appointment'),
                    self::SUBJECT_RESULT => $this->translate('Result'),
                    self::SUBJECT_OTHER => $this->translate('Other'),
                ),
                'empty_option'  => '-- ' . $this->translate('choose') . ' --'
                ),
            )
        );

        //todo: association as select
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
        $hydrator = new SupportHydrator($this->getEntityManager());
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
