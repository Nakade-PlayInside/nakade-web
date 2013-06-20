<?php
namespace User\Form;


use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use User\Entity\User;

/**
 * Form for adding or editing a new User
 */
class BirthdayForm extends AbstractForm
{
  
    /**
     * Constructor
     */        
    public function __construct()
    {
        
        //form name is LeagueForm
        parent::__construct($name='BirthdayForm');
        $this->setObject(new User());
        $this->setHydrator(new Hydrator());
        $this->init();
        $this->setInputFilter($this->getFilter());
    } 
   
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
               
        //birthday
        $this->add(
            array(
                'name' => 'birthday',
                'type' => 'Zend\Form\Element\Date',
                'options' => array(
                    'label' =>  $this->translate('Birthday (opt.):'),
                    'format' => 'Y-m-d',
                 ),
                'attributes' => array(
                     'min' => '1900-01-01',
                     'max' => date('Y-m-d'), 
                     'step' => '1',
                )
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
                 'name' => 'birthday',
                 'required' => false,
                 'validators' => array(
                     array('name'    => 'Date',
                           'options' => array (
                                  'format' => 'Y-m-d',
                           )  
                     ),
                 )
             )
         );
         
         
         return $filter;
    }
}
?>
