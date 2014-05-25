<?php
namespace Season\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use Season\Entity\Season;

/**
 * Form for making a new season
 */
class SeasonForm extends AbstractForm
{

    private $tieBreakerList;


    /**
     * @param array $tieBreaker
     */
    public function __construct(array $tieBreaker)
    {
        //form name is SeasonForm
        parent::__construct('SeasonForm');

        $this->tieBreakerList = array();
        /* @var $tieBreak \Season\Entity\TieBreaker */
        foreach ($tieBreaker as $tieBreak) {
            $this->tieBreakerList[$tieBreak->getId()]= $tieBreak->getName();
        }
        $this->init();
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

        //first tb
        $this->add(
            array(
                'name' => 'tiebreaker1',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('First tiebreaker') . ':',
                    'value_options' => $this->tieBreakerList
                ),
            )
        );

        //first tb
        $this->add(
            array(
                'name' => 'tiebreaker2',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Second tiebreaker') . ':',
                    'value_options' => $this->tieBreakerList
                ),
            )
        );

        //first tb
        $this->add(
            array(
                'name' => 'tiebreaker3',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Third tiebreaker') . ':',
                    'value_options' => $this->tieBreakerList
                ),
            )
        );

        $this->setDefaultElements();

    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new \Zend\InputFilter\InputFilter();

        $filter->add(
            array(
                 'name' => 'title',
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                     array('name'    => 'StringLength',
                           'options' => array (
                                  'encoding' => 'UTF-8',
                                  'max'  => '20',
                           )
                     ),
                  )
            )
        );

        return $filter;
    }
}
