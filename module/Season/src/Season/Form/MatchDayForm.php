<?php
namespace Season\Form;

use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;

class MatchDayForm extends BaseForm implements WeekDayInterface
{

    private $weekDays = array(
        self::MONDAY => 'Monday',
        self::TUESDAY => 'Tuesday',
        self::WEDNESDAY => 'Wednesday',
        self::THURSDAY => 'Thursday',
        self::FRIDAY => 'Friday',
        self::SATURDAY => 'Saturday',
        self::SUNDAY => 'Sunday',
    );

    private $matchDay;
    private $minDate;

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('MatchDayForm');

        $this->service = $service;
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

        $this->add(
            array(
                'name' => 'cycleInfo',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Cycle') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                )
            )
        );

        //match Day
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'matchDayInfo',
                'options' => array(
                    'label' => $this->translate('Match day') . ':',
                ),
                'attributes' => array(
                    'readonly' => 'readonly',
                ),
            )
        );

        //match Day
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
        $filter = new InputFilter();
        return $filter;
    }


    /**
     * @param \DateTime $minDate
     */
    public function setMinDate($minDate)
    {
        $today = new \DateTime();
        if ($today > $minDate) {
            $minDate = $today;
        }

        if ($minDate->format('N') != $this->matchDay) {
            $next = sprintf('next %s', $this->weekDays[$this->matchDay]);
            $minDate->modify($next);
        }

        $this->minDate = $minDate;
    }

    /**
     * @return \DateTime
     */
    public function getMinDate()
    {
        return $this->minDate;
    }

}

