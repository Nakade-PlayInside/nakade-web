<?php
namespace League\Form;

use Zend\InputFilter\InputFilter;

class WinnerFilter extends InputFilter
{
    
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
