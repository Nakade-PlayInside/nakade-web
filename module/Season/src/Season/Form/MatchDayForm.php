<?php
namespace Season\Form;

use Season\Form\Hydrator\MatchDayHydrator;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;

class MatchDayForm extends BaseForm
{

    private $minDate;
    private $maxDate;

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('MatchDayForm');

        $this->setFieldSetService($service);

        $hydration = new MatchDayHydrator();
        $this->setHydrator($hydration);
        $this->setInputFilter($this->getFilter());
    }

    /**
     * @param \Season\Entity\MatchDay $object
     */
    public function bindEntity($object)
    {
        $date = $object->getDate();
        $minDate = clone $date;
        $maxDate = clone $date;
        $this->setMinDate($minDate->modify('-2 week')->format('Y-m-d'));
        $this->setMaxDate($maxDate->modify('+2 week')->format('Y-m-d'));

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
                'name' => 'round',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' => $this->translate('Round') . ':',
                ),
                'attributes' => array(
                    'readonly' => 'readonly',
                ),
            )
        );

        //start date
        $this->add(
            array(
                'name' => 'date',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' => $this->translate('Date'),
                    'format' => 'Y-m-d',
                ),
                'attributes' => array(
                    'min'   => $this->getMinDate(),
                    'step'  => '1',
                    'max'   => $this->getMaxDate()
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
     * @return \DateTime
     */
    public function getMinDate()
    {
        return $this->minDate;
    }

    /**
     * @param string $minDate
     */
    public function setMinDate($minDate)
    {
        $this->minDate = $minDate;
    }

    /**
     * @param string $maxDate
     */
    public function setMaxDate($maxDate)
    {
        $this->maxDate = $maxDate;
    }

    /**
     * @return \DateTime
     */
    public function getMaxDate()
    {
        return $this->maxDate;
    }

}

