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

    /**
     * @param array $fieldSets
     */
    public function __construct(array $fieldSets)
    {
        //form name is SeasonForm
        parent::__construct('SeasonForm');

        //byoyomi, time and buttons
        foreach ($fieldSets as $fieldSet) {
            $this->add($fieldSet);
        }
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
