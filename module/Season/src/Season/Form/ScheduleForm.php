<?php
namespace Season\Form;

use Season\Services\RepositoryService;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;

class ScheduleForm extends BaseForm
{
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    const DAILY = 1;
    const WEEKLY = 7;
    const FORTNIGHTLY = 14;
    const MONTHLY = 30;

    private $weekDays = array(
        self::MONDAY => 'Monday',
        self::TUESDAY => 'Tuesday',
        self::WEDNESDAY => 'Wednesday',
        self::THURSDAY => 'Thursday',
        self::FRIDAY => 'Friday',
        self::SATURDAY => 'Saturday',
        self::SUNDAY => 'Sunday',
    );

    private $cycleInfo;
    private $matchDay;
    private $seasonStartDate;
    private $minDate;
    private $noOfMatchDays;

    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        parent::__construct('ScheduleForm');

        $this->service = $service;
        $this->repository = $repository;
        $this->setInputFilter($this->getFilter());
    }

    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init()
    {

        $this->prepareForm();

        //association
        $this->add(
            array(
                'name' => 'associationName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Association') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getAssociationName()
                )
            )
        );

        //number
        $this->add(
            array(
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season no.') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getSeasonNumber()
                )
            )
        );

        $this->add(
            array(
                'name' => 'seasonStartDate',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season start date') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->getSeasonStartDate()->format('d.m.Y')
                )
            )
        );

        $this->add(
            array(
                'name' => 'cycle',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Match cycle') . ':'),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->cycleInfo
                )
            )
        );

        //match Day
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'matchDay',
                'options' => array(
                    'label' => $this->translate('Match day'),
                ),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->matchDay
                ),
            )
        );

        //match Day
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'matchDay',
                'options' => array(
                    'label' => $this->translate('No of match days'),
                ),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->noOfMatchDays
                ),
            )
        );

        //start date
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Date',
                'name' => 'startDate',
                'options' => array(
                    'label' => $this->translate('Match start date'),
                    'format' => 'Y-m-d',
                ),
                'attributes' => array(
                     'min'   => $this->getMinDate()->format('Y-m-d'),
                     'step'  => '1',
                     'value' => $this->getMinDate()->format('Y-m-d')
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

        if (!is_null($this->getSeason())) {
            $this->associationName = $this->getSeason()->getAssociation()->getName();
            $this->seasonNumber = $this->getSeason()->getNumber();
            $this->cycleInfo = $this->getCycleInfo($this->getSeason()->getAssociation()->getSeasonDates()->getCycle());
            $this->matchDay = $this->getMatchDay($this->getSeason()->getAssociation()->getSeasonDates()->getDay());
            $this->setSeasonStartDate($this->getSeason()->getStartDate());
            $this->setMinDate($this->getSeason()->getStartDate());
            $this->noOfMatchDays = $this->getNoOfMatchdays($this->getSeason()->getId());

        }

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
     * @param int $days
     *
     * @return string
     */
    private function getCycleInfo($days)
    {
        $info = $this->translate('every %NUMBER% days');
        $info = str_replace('%NUMBER%', $days, $info);

        switch ($days) {

            case self::DAILY:
                $info = $this->translate('daily');
                break;

            case self::WEEKLY:
                $info = $this->translate('weekly');
                break;

            case self::FORTNIGHTLY:
                $info = $this->translate('fortnightly');
                break;

            case self::MONTHLY:
                $info = $this->translate('monthly');
                break;
        }

        if ($days % 7 == 0 && $days/7 > 2) {
            $info = $this->translate('every %NUMBER% weeks');
            $weeks = $days/7;
            $info = str_replace('%NUMBER%', $weeks, $info);
        }

        return $info;
    }

    /**
     * @return array
     */
    private function getTranslatedWeekdays()
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
     * @param int $day
     *
     * @return string
     */
    private function getMatchDay($day)
    {
        $weekDays = $this->getTranslatedWeekdays();
        return $weekDays[$day];
    }

    /**
    * @param \DateTime $seasonStartDate
    */
    public function setSeasonStartDate($seasonStartDate)
    {
        $this->seasonStartDate = $seasonStartDate;
    }

    /**
     * @return \DateTime
     */
    public function getSeasonStartDate()
    {
        return $this->seasonStartDate;
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

    /**
     * @return RepositoryService
     */
    public function getRepositoryService()
    {
        return $this->repository;
    }

    /**
     * @param int $seasonId
     *
     * @return int
     */
    public function getNoOfMatchdays($seasonId)
    {
        /* @var $mapper \Season\Mapper\ParticipantMapper */
        $mapper = $this->getRepositoryService()->getMapper(RepositoryService::PARTICIPANT_MAPPER);
        return $mapper->getNoOfMatchDaysBySeason($seasonId);
    }

}

