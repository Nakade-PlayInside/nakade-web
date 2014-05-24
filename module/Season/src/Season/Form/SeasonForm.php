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

    private $previousSeason;
    private $tieBreakerList;


    /**
     * constructor
     */
    public function __construct(array $tieBreaker, Season $season)
    {
        //form name is SeasonForm
        parent::__construct('SeasonForm');

        $this->previousSeason = $season;
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
                'options' => array(
                    'label' =>  $this->translate('Association:'),
                ),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->previousSeason->getAssociation()->getName(),
                )
            )
        );

        //association id
        $this->add(
            array(
                'name' => 'association',
                'type' => 'Zend\Form\Element\Hidden',
                'attributes' => array(
                    'value' => $this->previousSeason->getAssociation()->getId(),
                )
            )
        );

        //number
        $this->add(
            array(
                'name' => 'number',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Season no:'),
                ),
                'attributes' => array(
                    'readonly' => 'readonly',
                    'value' => $this->previousSeason->getNumber()+1,
                )
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
                    //'max'  => $this->maxDate->format('Y-m-d'),
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
                    'label' =>  $this->translate('Winning points:'),
                    'value_options' => array (
                        1 => '1',
                        2 => '2',
                        3 => '3'
                    )
                ),
                'attributes' => array(
                    'value' => $this->previousSeason->getWinPoints(),
                )
            )
        );

        //komi
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'komi',
                'options' => array(
                    'label' =>  $this->translate('Komi') . ':',
                ),
                'attributes' => array(
                    'value' => $this->previousSeason->getKomi(),
                )
            )
        );

        //first tb
        $this->add(
            array(
                'name' => 'tiebreaker1',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('First tiebreaker:'),
                    'value_options' => $this->tieBreakerList
                ),
                'attributes' => array(
                    'value' => $this->previousSeason->getTieBreaker1()->getId(),
                )
            )
        );

        //first tb
        $this->add(
            array(
                'name' => 'tiebreaker2',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Second tiebreaker:'),
                    'value_options' => $this->tieBreakerList
                ),
                'attributes' => array(
                    'value' => $this->previousSeason->getTieBreaker2()->getId(),
                )
            )
        );

        //first tb
        $this->add(
            array(
                'name' => 'tiebreaker3',
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' =>  $this->translate('Third tiebreaker:'),
                    'value_options' => $this->tieBreakerList
                ),
                'attributes' => array(
                    'value' => $this->previousSeason->getTieBreaker3()->getId(),
                )
            )
        );

        //cross-site scripting hash protection
        //this is handled by ZF2 in the background - no need for server-side
        //validation
        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )
        );

        //submit button
        $this->add(
            array(
                'name' => 'Send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Submit'),

                ),
            )
        );

        //cancel button
        $this->add(
            array(
                'name' => 'cancel',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Cancel'),

                ),
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
