<?php
namespace Season\Form;

use Zend\InputFilter\InputFilter;

class WinnerFilter extends InputFilter
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->add(
            array(
                'name'       => 'winner',
                'required'   => false,
            )
        );


    }
}
