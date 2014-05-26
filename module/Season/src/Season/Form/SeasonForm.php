<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Form\Fieldset\ButtonFieldset;
use Season\Form\Fieldset\TieBreakerFieldset;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;


/**
 * Form for making a new season
 */
class SeasonForm extends AbstractForm
{

    private $byoyomi;
    private $tieBreaker;
    private $buttons;


    /**
     * @param array $byoyomi
     */
    public function __construct(array $byoyomi)
    {
        //form name is SeasonForm
        parent::__construct('SeasonForm');

        $this->byoyomi = $this->getByoyomiList($byoyomi);
        $this->setInputFilter($this->getFilter());
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
                    'min'  => \date('Y-m-d'),
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
                'options' => array('label' =>  $this->translate('Komi') . ':'),
            )
        );

        $this->addTimeFieldSet();
        $this->add($this->getTieBreaker());
        $this->add($this->getButtons());
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
                    'value_options' => $this->byoyomi
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
        $filter = new \Zend\InputFilter\InputFilter();

        $filter->add(
            array(
                 'name' => 'komi',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'Float'),
                  )
            )
        );

        return $filter;
    }

    /**
     * @param TieBreakerFieldset $tieBreaker
     */
    public function setTieBreaker(TieBreakerFieldset $tieBreaker)
    {
        $this->tieBreaker = $tieBreaker;
    }

    /**
     * @return TieBreakerFieldset
     */
    public function getTieBreaker()
    {
        return $this->tieBreaker;
    }

    /**
     * @param ButtonFieldset $buttons
     */
    public function setButtons(ButtonFieldset $buttons)
    {
        $this->buttons = $buttons;
    }

    /**
     * @return ButtonFieldset
     */
    public function getButtons()
    {
        return $this->buttons;
    }



    /**
     * @param array $byoyomiList
     *
     * @return array
     */
    private function getByoyomiList(array $byoyomiList)
    {
        $list = array();
        /* @var $byoyomi \Season\Entity\Byoyomi */
        foreach ($byoyomiList as $byoyomi) {
            $list[$byoyomi->getId()]= $byoyomi->getName();
        }
        return $list;
    }
}
