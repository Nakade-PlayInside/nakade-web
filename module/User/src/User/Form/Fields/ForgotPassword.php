<?php
namespace User\Form\Fields;

class ForgotPassword extends DefaultField
{
        /**
     * get array of field content
     *
     * @return array
     */
    public function getField()
    {

        //birthday
       return array(
           array(
               'name' => 'email',
               'type' => 'Zend\Form\Element\Email',
               'options' => array(
                   'label' =>  $this->translate('email:'),

               ),
               'attributes' => array(
                   'multiple' => false,
               )
           )
        );
    }

    /**
     * get the InputFilter
     *
     * @return array
     */
    public function getFilter()
    {
        return array(
            array(
                'name' => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StripNewLines'),
                ),
                'validators' => array(
                    array('name' => 'StringLength',
                        'options' => array (
                            'encoding' => 'UTF-8',
                            'min'  => '6',
                            'max'  => '120',
                        )
                    ),
                    array('name' => 'EmailAddress',
                        'break_chain_on_failure' => true,
                    ),

                )
            )
         );
    }
}

