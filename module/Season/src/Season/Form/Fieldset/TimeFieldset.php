<?php
namespace Season\Form\Fieldset;

use Nakade\Abstracts\AbstractFieldset;

/**
 * Class TimeFieldset
 *
 * @package Season\Form\Fieldset
 */
class TimeFieldset extends AbstractFieldset
{

    private $byoyomi;

    /**
     * @param array $byoyomiList
     */
    public function __construct(array $byoyomiList)
    {
        //form name is SeasonForm
        parent::__construct('time');

        $this->byoyomi = array();

        /* @var $type \Season\Entity\Byoyomi */
        foreach ($byoyomiList as $type) {
            $this->byoyomi[$type->getId()]= $type->getName();
        }
        $this->init();
    }

    /**
     * init fieldSet
     */
    public function init()
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
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}
