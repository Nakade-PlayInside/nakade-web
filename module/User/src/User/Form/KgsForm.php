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
class KgsForm extends AbstractForm
{
  
    /**
     * Constructor
     */        
    public function __construct()
    {
        parent::__construct();
        $this->setObject(new User());
        $this->setHydrator(new Hydrator());
        
    } 
   
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
        
        //cross-site scripting hash protection
        //this is handled by ZF2 in the background - no need for server-side 
        //validation 
        $this->add(
            array(
                'name' => 'csrf',
                'type'  => 'Zend\Form\Element\Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )    
        );
       
        //submit button
        $this->add(
            array(
                'name' => 'send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Submit'),

                ),
            )
        );
        
         //cancel button
        $this->add(
            array(
                'name' => 'cancel',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Cancel'),

                ),
            )
        );
       
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
