<?php
namespace User\Form;

use Nakade\Abstracts\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods as Hydrator;
use User\Entity\User;

/**
 * Form for password changing.
 * Use a factory for needed settings after constructing.
 * Successive settings: setEntityManager(), setInputFilter(), init().
 * Use bindingEntity for setting values.
 */
class PasswordForm extends AbstractForm
{
  
    /**
     * Constructor
     */        
    public function __construct()
    {
        parent::__construct($name='PasswordForm');
        $this->setObject(new User());
        $this->setHydrator(new Hydrator());
    } 
   
    /**
     * init the form. It is neccessary to call this function
     * before using the form.
     */
    public function init() {
       
         //password
        $this->add(
            array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' =>  $this->translate('enter new password:'),
                    
                ),
                
            )
        );
        
        $this->add(
            array(
                'name' => 'repeat',
                'type' => 'Zend\Form\Element\Password',
                'options' => array(
                    'label' =>  $this->translate('repeat new password:'),
                    
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
                 'name' => 'password',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                     array('name' => 'StripNewLines'),
                  ),
                 'validators' => array(
                    array('name' => 'StringLength',
                          'break_chain_on_failure' => true,
                          'options' => array (
                              'encoding' => 'UTF-8', 
                              'min'  => '6',
                              'max'  => '50',
                          )  
                     ),
                    array('name' => 'Identical',
                          'break_chain_on_failure' => true,
                          'options' => array (
                              'token' => 'repeat', 
                          )  
                     ), 
                    array('name' => 'User\Form\Validator\PasswordComplexity',
                          'break_chain_on_failure' => true,
                          'options' => array (
                              'length'   => '8',
                              'treshold' => '80',
                          )
                    ),
                    array('name' => 'User\Form\Validator\CommonPassword',
                          'break_chain_on_failure' => true,
                          'options' => array (  
                               'commons'  => array(
                                                'password',
                                                '123456',
                                                'qwert',
                                                'abc123',
                                                'letmein',
                                                'myspace',
                                                'monkey',
                                                'iloveyou',
                                                'sunshine',
                                                'trustno1',
                                                'welcome',
                                                'shadow',
                              )
                         )       
                    ),
                     
                 )
             )
         );
         
         return $filter;
    }
}
?>
