<?php
namespace Season\Form;

use Season\Form\Hydrator\MatchDayHydrator;
use Season\Services\SeasonFieldsetService;
use Doctrine\ORM\EntityManager;
use \Zend\InputFilter\InputFilter;

class MatchDayForm extends BaseForm implements WeekDayInterface
{

    /**
     * @param SeasonFieldsetService $service
     * @param EntityManager         $em
     */
    public function __construct(SeasonFieldsetService $service, EntityManager $em)
    {
        parent::__construct('MatchDayForm');

        $this->service = $service;
        $hydration = new MatchDayHydrator($em);
        $this->setHydrator($hydration);

        $this->setInputFilter($this->getFilter());
    }

    /**
     * @param \Season\Entity\Schedule $object
     */
    public function bindEntity($object)
    {
        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);

    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        //association
        $this->add(
            array(
                'name' => 'seasonInfo',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                )
            )
        );

        //rounds
        $this->add(
            array(
                'name' => 'noOfMatchDays',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' => $this->translate('No of rounds') . ':',
                ),
                'attributes' => array(
                    'readonly' => 'readonly',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'cycle',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Cycle') . ':',
                    'value_options' => $this->getCycle()
                ),
                'attributes' => array(
                    'size' => 1,
                )
            )
        );

        //match Day
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'day',
                'options' => array(
                    'label' => $this->translate('Match day') . ':',
                    'value_options' => $this->getWeekdays()
                ),
                'attributes' => array(
                    'size' => 1
                ),
            )
        );

        //start date
        $this->add(
            array(
                'name' => 'startDate',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' => $this->translate('Start date'),
                    'format' => 'Y-m-d',
                ),
                'attributes' => array(
                     'min'   => \date('Y-m-d'),
                     'step'  => '1',
                ),
            )
        );

        //time
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Time',
                'name' => 'time',
                'options' => array('label' => $this->translate('Time') . ':'),
                'attributes' => array(
                     'step'  => '900'
                )
            )
        );

        $this->add($this->getService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET));
    }

    /**
     * you have to init this after setting season or league
     */
    protected function prepareForm()
    {
    }


    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        //todo: values for time and start date
        $filter = new InputFilter();
        return $filter;
    }

    /**
     * @return array
     */
    public function getWeekdays()
    {
        return array(
            self::MONDAY => $this->translate('Monday'),
            self::TUESDAY => $this->translate('Tuesday'),
            self::WEDNESDAY => $this->translate('Wednesday'),
            self::THURSDAY => $this->translate('Thursday'),
            self::FRIDAY => $this->translate('Friday'),
            self::SATURDAY => $this->translate('Saturday'),
            self::SUNDAY => $this->translate('Sunday')
        );
    }

    /**
     * @return array
     */
    public function getCycle()
    {
        //todo: special calculation for weekdays is needed
       return array(
            self::DAILY => $this->translate('daily'),
        //    self::WEEKDAYS => $this->translate('on weekdays'),
            self::WEEKLY => $this->translate('weekly'),
            self::FORTNIGHTLY => $this->translate('fortnightly'),
            self::ALL_THREE_WEEKS => $this->translate('all 3 weeks'),
            self::MONTHLY => $this->translate('monthly'),
       );
    }

}

