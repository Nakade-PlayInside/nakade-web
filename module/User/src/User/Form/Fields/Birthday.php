<?php
namespace User\Form\Fields;

class Birthday extends DefaultField
{
        /**
     * get array of field content
     *
     * @return array
     */
    public function getField() {

        //birthday
       return array(
            'name' => 'birthday',
            'type' => 'Zend\Form\Element\Date',
            'options' => array(
                'label' =>  $this->translate('Birthday:'),
                'format' => 'Y-m-d',
             ),
            'attributes' => array(
                 'min' => '1900-01-01',
                 'max' => date('Y-m-d'),
                 'step' => '1',
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
             'name' => 'birthday',
             'required' => false,
             'validators' => array(
                 array('name'    => 'Date',
                       'options' => array (
                              'format' => 'Y-m-d',
                       )
                 ),
             )
         );
    }
}
?>
