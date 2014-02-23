<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use User\Entity\User;

/**
 * Form for changing email adress.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class KgsForm extends DefaultForm
{
  
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
        //email
        $this->add(
            array(
                'name' => 'kgs',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('KGS (opt.):'),
                    
                ),

            )
        );
        
        $this->setDefaultFields();
       
    } 
    
    
    
     
    /**
     * get the InputFilter
     * 
     * @return \Zend\InputFilter\InputFilter
     */
    public function getFilter()
    {
        $filter = new \Zend\InputFilter\InputFilter();
        $filter->add(
            array(
                'name' => 'kgs',
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
                            'max'  => '50',
                        )
                    ),
                    array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array(
                            'entity'    => 'User\Entity\User',
                            'property' => 'kgs',
                            'exclude'  => $this->getIdentifierValue(),
                            'adapter'  => $this->getEntityManager(),
                        )
                    )
                )
            )
        );


        return $filter;
    }
}
?>
