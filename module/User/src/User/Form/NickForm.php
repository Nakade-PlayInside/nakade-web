<?php
namespace User\Form;


use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use User\Entity\User;

/**
 * Form for adding or editing a new User
 */
class NickForm extends AbstractForm
{
  
    /**
     * Constructor
     */        
    public function __construct()
    {
        parent::__construct($name='UserForm');
        $this->setObject(new User());
        $this->setHydrator(new Hydrator());
       
    } 
   
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
        //nick name
        $this->add(
            array(
                'name' => 'nickname',
                'type' => 'Zend\Form\Element\Text',
                'options' => array(
                    'label' =>  $this->translate('Nick (opt.):'),
                ),
            )
        );
        
        //anonym
        $this->add(
            array(
                'name' => 'anonym',
                'type' => 'Zend\Form\Element\Checkbox',
                'options' => array(
                    'label' =>  $this->translate('use nick always (anonymizer):'),
                    'checked_value' => true,
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
                'name' => 'Send',
                'type'  => 'Zend\Form\Element\Submit',
                'attributes' => array(
                    'value' =>   $this->translate('Submit'),

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
                 'name' => 'nickname',
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
                     array(
                        'name'     => 'User\Form\Validator\DBNoRecordExist',
                        'options' => array( 
                            'entity'    => 'User\Entity\User',
                            'property' => 'nickname',
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
