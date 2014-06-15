<?php
namespace League\Form;

use Nakade\Abstracts\AbstractForm;
use Season\Entity\Match;
use Season\Services\SeasonFieldsetService;
use Zend\InputFilter\InputFilter;

/**
 * Class MatchDayForm
 *
 * @package League\Form
 */
class MatchDayForm extends AbstractForm
{

    private $date;

    /**
     * @param SeasonFieldsetService $service
     */
    public function __construct(SeasonFieldsetService $service)
    {
        parent::__construct('MatchDayForm');

        $this->service = $service;
       // $this->setInputFilter($this->getFilter());
        $this->date = date('Y-m-d');

    }

    /**
     * init the form. It is necessary to call this function
     * before using the form.
     */
    public function init()
    {
     //   $this->setAttribute('method', 'post');

        //info
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'id',
                'options' => array('label' => $this->translate('Match Id') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //info
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'matchDay',
                'options' => array('label' => $this->translate('Match Day') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //info
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'blackPlayer',
                'options' => array('label' => $this->translate('Black') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //info
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'whitePlayer',
                'options' => array('label' => $this->translate('White') . ':'),
                'attributes' => array('readonly' => 'readonly')
            )
        );

        //date
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Date',
                'name' => 'matchDate',
                'options' => array(
                    'label' => $this->translate('Date') . ':',
                    'format' => 'Y-m-d',
                ),
                'attributes' => array(
                     'step'  => '1',
                     'value' => $this->date
                ),
            )
        );


        //time
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Time',
                'name' => 'matchTime',
                'options' => array('label' => $this->translate('Time') . ':'),
                'attributes' => array('step'  => '900')
            )
        );

        //check
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'changeColors',
                'options' => array('label' => $this->translate('Change Colors')),
            )
        );

        $this->add($this->getService()->getFieldset(SeasonFieldsetService::BUTTON_FIELD_SET));

    }

    /**
     * @return SeasonFieldsetService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new InputFilter();

        $filter->add(
            array(
                'name' => 'changeColors',
                'required' => false
            )
        );

        $filter->add(
            array(
                'name' => 'matchTime',
                'required' => false
            )
        );


        return $filter;

    }
}
