<?php
namespace Moderator\Form;

use Moderator\Controller\SupportTypeInterface;
use Moderator\Entity\SubjectInterface;
use Moderator\Form\Hydrator\SupportHydrator;
use Zend\InputFilter\InputFilter;
/**
 * Class SupportForm
 *
 * @package Moderator\Form
 */
class SupportForm extends BaseForm implements SupportInterface, SubjectInterface, SupportTypeInterface
{

    private $type = self::ADMIN_TICKET;

    /**
     * @param \Moderator\Entity\SupportRequest $object
     */
    public function bindEntity($object)
    {
        $this->type = $object->getType()->getId();

        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }

    public function init()
    {

        $this->add(
            array(
            'name' => self::SUBJECT,
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' =>  $this->translate('Subject') . ':',
                'value_options' => $this->getSubjectOptions(),
                'empty_option'  => '-- ' . $this->translate('choose') . ' --'
                ),
            )
        );

        //todo: get all associations by user
        //todo: association as select
        if ($this->isLeagueManagerTicket()) {

            $this->add(
                array(
                    'name' => self::ASSOCIATION,
                    'type' => 'Zend\Form\Element\Hidden'
                )
            );
        }


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

    /**
     * @return bool
     */
    private function isLeagueManagerTicket()
    {
        return $this->type == self::LEAGUE_MANAGER_TICKET;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }


    private function getSubjectOptions()
    {

        switch ($this->getType()) {

            case self::ADMIN_TICKET:
                $options = array(
                    self::SUBJECT_PROFILE => $this->translate('Profile'),
                    self::SUBJECT_RIGHTS => $this->translate('Rights'),
                    self::SUBJECT_BUG => $this->translate('Bug report'),
                    self::SUBJECT_OTHER => $this->translate('Other'),
                );
                break;

            case self::LEAGUE_MANAGER_TICKET:
                $options = array(
                    self::SUBJECT_APPOINTMENT => $this->translate('Appointment'),
                    self::SUBJECT_RESULT => $this->translate('Result'),
                    self::SUBJECT_OTHER => $this->translate('Other'),
                );
                break;

            case self::REFEREE_TICKET:
                $options = array(
                    self::SUBJECT_RULES => $this->translate('Rules'),
                    self::SUBJECT_BEHAVIOR => $this->translate('Inappropriate behavior'),
                    self::SUBJECT_OTHER => $this->translate('Other'),
                );
                break;

            default:
                $options = array(
                    self::SUBJECT_OTHER => $this->translate('Other')
                );
        }

        return $options;
    }
}
