<?php
namespace Season\Form;

use Zend\InputFilter\InputFilter;

class PointsFilter extends InputFilter
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->add(
            array(
                'name'       => 'points',
                'required'   => true,
                'filters'    => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'PregReplace',
                          'options' => array(
                              'pattern' => '/,/',
                              'replacement' => '.',
                          )
                    ),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Float',
                    ),
                    array(
                        'name'    => 'GreaterThan',
                        'options' => array(
                            'min' => 0
                        )
                    ),
                ),


            )
        );


    }
}
