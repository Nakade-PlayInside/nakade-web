<?php
namespace Season\Form;

use Season\Services\RepositoryService;
use Season\Form\Hydrator\SeasonHydrator;
use Season\Services\SeasonFieldsetService;
use \Zend\InputFilter\InputFilter;
/**
 * Class SeasonForm
 *
 * @package Season\Form
 */
class SeasonForm extends BaseForm
{

    private $extraTime = array();
    private $minDate;


    /**
     * @param SeasonFieldsetService $service
     * @param RepositoryService     $repository
     */
    public function __construct(SeasonFieldsetService $service, RepositoryService $repository)
    {
        //form name is SeasonForm
        parent::__construct('SeasonForm');

        $this->setFieldSetService($service);
        $this->setRepository($repository);

        $this->setMinDate(\date('Y-m-d'));

        $this->setInputFilter($this->getFilter());
        $byoyomiList = $this->getSeasonMapper()->getByoyomi();
        /* @var $object \Season\Entity\Byoyomi */
        foreach ($byoyomiList as $object) {
            $this->extraTime[$object->getId()]= $object->getName();
        }

        $hydrator = new SeasonHydrator($this->getEntityManager());
        $this->setHydrator($hydrator);
    }

    /**
     * @param \Season\Entity\Season $object
     */
    public function bindEntity($object)
    {
        if (!is_null($object->getStartDate())) {
            $this->setMinDate($object->getStartDate()->format('Y-m-d'));
        }

        $this->init();
        $this->setInputFilter($this->getFilter());
        $this->bind($object);
    }


    /**
     * init
     */
    public function init()
    {
        //association
        $this->add(
            array(
                'name' => 'associationName',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Association') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //number
        $this->add(
            array(
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Season no.') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //date
        $this->add(
            array(
                'name' => 'startDate',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' =>  $this->translate('Start date').":",
                    'format' => 'Y-m-d'
                ),
                'attributes' => array(
                    'min'  => $this->getMinDate(),
                    'step' => '1', // days; default step interval is 1 day
                )

            )
        );

        //winPoints
        $this->add(
            array(
                'name' => 'winPoints',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Winning points') . ':',
                    'value_options' => array (
                        1 => '1',
                        2 => '2',
                        3 => '3'
                    )
                ),
            )
        );

        //komi
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'komi',
                'options' => array(
                    'label' =>  $this->translate('Komi') . ':'
                )
            )
        );

        $this->addTimeFieldSet();
        $this->add($this->getTieBreakerFieldSet());
        $this->add($this->getButtonFieldSet());
    }

    /**
     * add fieldSet time until filter setting is clear
     */
    private function addTimeFieldSet()
    {
        //baseTime
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'baseTime',
                'options' => array('label' =>  $this->translate('Base time in minutes') . ':'),
            )
        );

        //byoyomi type
        $this->add(
            array(
                'name' => 'byoyomi',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Extra time') . ':',
                    'value_options' => $this->getExtraTime()
                ),
            )
        );

        //period
        $this->add(
            array(
                'name' => 'period',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Periods') . ':')
            )
        );

        //additionalTime
        $this->add(
            array(
                'name' => 'additionalTime',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Extra time') . ':')
            )
        );

        //moves or stones
        $this->add(
            array(
                'name' => 'moves',
                'type' => 'Zend\Form\Element\Text',
                'options' => array('label' =>  $this->translate('Moves per extra time') . ':')
            )
        );
    }


    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add($this->getValidation('baseTime'));
        $filter->add($this->getValidation('additionalTime'));
        $filter->add($this->getValidation('period'));
        $filter->add($this->getValidation('moves'));
        $filter->add($this->getValidation('komi', 'Float'));


        return $filter;
    }

    /**
     * @param string $name
     * @param string $validation
     *
     * @return array
     */
    private function getValidation($name, $validation='Digits')
    {

        return array(
            'name' => $name,
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StripNewLines'),
            ),
            'validators' => array(
                array('name'    => $validation),
            )
        );
    }

    /**
     * @param string $minDate
     */
    public function setMinDate($minDate)
    {
        $this->minDate = $minDate;
    }

    /**
     * @return string
     */
    public function getMinDate()
    {
        return $this->minDate;
    }

    /**
     * @return array
     */
    public function getExtraTime()
    {
        return $this->extraTime;
    }

}
