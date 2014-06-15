<?php
namespace Season\Form\Fieldset;

use Nakade\Abstracts\AbstractFieldset;

/**
 * Class TieBreakerFieldset
 *
 * @package Season\Form\Fieldset
 */
class TieBreakerFieldset extends AbstractFieldset
{

    private $tieBreakerList;


    /**
     * @param array $tieBreaker
     */
    public function __construct(array $tieBreaker)
    {
        //form name is SeasonForm
        parent::__construct('tiebreak');

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

    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}
