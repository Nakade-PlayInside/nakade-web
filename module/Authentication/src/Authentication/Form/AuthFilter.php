<?php
namespace Authentication\Form;

use Zend\InputFilter\InputFilter;

class AuthFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(
            array(
                'name'       => 'identity',
                'required'   => true,
                'filters'    => array(
                    array('name'    => 'StripTags'),
                ),    
            )    
        );

        $this->add(
            array(
                'name'       => 'password',
                'required'   => true,
                'filters'    => array(
                    array('name'    => 'StripTags'),
                ),
            
            )
        );

       
    }
}
